<?php

namespace App\Models;

use App\Models\FormRequest;
use App\Models\FormRequestMedia;
use App\Models\FormU;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Crypt;

class Media extends Model
{
    protected $table = 'medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'original_name', 'media_name', 'size', 'md5', 'resolution', 'extension', 'flip_x', 'flip_y', 'alt', 'title', 'description', 'type', 'admin_id', 'status', 'public', 'language', 'robots'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static function languages($default = 'Sélectionnez une langue')
    {
        return [
            '' => $default,
            'en' => 'Anglais',
            'fr' => 'Français',
        ];
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\MediaCategory', 'medias_medias_categories', 'media_id', 'media_category_id');
    }

    public function types()
    {
        return $this->hasMany('App\Models\MediaType');
    }

    public function link()
    {
        return $this->hasMany('App\Models\MediaLink', 'media_id_1');
    }

    public function linked()
    {
        return $this->hasMany('App\Models\MediaLink', 'media_id_2');
    }

    public function requests()
    {
        return $this->hasMany('App\Models\FormRequestMedia', 'media_id');
    }

    public function totalRequestsDownloads()
    {
        return $this->requests->sum('downloads');
    }

    public function hasCategory($slug)
    {
        foreach ($this->categories as $c) {
            if ($c->slug == $slug) {
                return true;
            }
        }
        return false;
    }

    public function downloadsRequestsInfos()
    {
        $requests = FormRequest::join('forms_requests_medias', 'forms_requests_medias.request_id', '=', 'forms_requests.id')
                               ->where('forms_requests_medias.media_id', $this->id)
                               ->whereNotNull('forms_requests.page_id')
                               ->groupBy('forms_requests.page_id')
                               ->get(['forms_requests.*']);

        $total_downloads = $this->totalRequestsDownloads();
        $downloads_counter = 0;
        $downloads = '';
        foreach ($requests as $r) {
            $requests_medias = FormRequestMedia::join('forms_requests', 'forms_requests_medias.request_id', '=', 'forms_requests.id')
                                               ->where('forms_requests.page_id', $r->page_id)
                                               ->where('forms_requests_medias.media_id', $this->id)
                                               ->sum('forms_requests_medias.downloads');
            $page = Page::find($r->page_id);
            if ($page && $requests_medias && $requests_medias > 0) {
                $downloads .= $page->getAttr('name').': '.$requests_medias.' download(s)<br/>';
                $downloads_counter += $requests_medias;
            }
        }

        if ($downloads_counter < $total_downloads) {
            $downloads .= ($total_downloads - $downloads_counter).' other download(s)<br/>';
        }

        return $downloads;
    }

    public function getLinksAttribute()
    {
        return $this->link->merge($this->linked);
    }

    public function linksArray($ids = false)
    {
        $array = [];
        foreach ($this->links as $l) {
            if ($l->media_id_1 == $this->id) {
                if (!$ids) {
                    $array[$l->media_id_2] = '#'.$l->media_id_2.' - '.$l->media2->media_name;
                } else {
                    $array[] = $l->media_id_2;
                }
            } else {
                if (!$ids) {
                    $array[$l->media_id_1] = '#'.$l->media_id_1.' '.$l->media1->media_name;
                } else {
                    $array[] = $l->media_id_1;
                }
            }
        }
        return $array;
    }

    public function hasType($type_id, $type)
    {
        return $this->types->where('type_id', $type_id)->where('type', getClass($type))->count() ? true : false;
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeChecked($query)
    {
        return $query->where('status', 'checked');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    public function route($width = null, $height = null, $file_preview = false)
    {
        if ($this->type == 'picture') {
            if (!$width || !$height) {
                $file_path = config('paths.medias').$this->name;
                if (file_exists($file_path)) {
                    list($width, $height) = getimagesize($file_path);
                } else {
                    $dimensions = explode('x', $this->resolution);
                    $width = isset($dimensions[0]) ? $dimensions[0] : null;
                    $height = isset($dimensions[1]) ? $dimensions[1] : null;
                }
            }
            return route('media_picture', [$this->id, $width, $height, $this->formattedOriginalName()]);
        } elseif ($this->extension == 'pdf' && $file_preview) {
            return route('media_file_preview', [$this->id, $width, $height, str_replace('.pdf', '', $this->formattedOriginalName())]);
        } else {
            return route('media_file', [$this->id, $this->formattedOriginalName()]);
        }
    }

    public function maxWidthRoute($max_width)
    {
        $route = $this->route();
        if ($this->type == 'picture') {
            $resolution = explode('x', $this->resolution);
            if (count($resolution) == 2) {
                $width = $resolution[0];
                $height = $resolution[1];
                if ($width > $max_width) {
                    $media_width = $max_width;
                    $media_height = floor(($max_width * $height) / $width);
                    $route = $this->route($media_width, $media_height);
                }
            }
        }
        return $route;
    }

    public function adminListingPreview()
    {
        $size = 60;
        $return = '<a href="'.route('medias.edit', [$this->id]).'">';
        if ($this->type == 'picture') {
            $return .= '<img src="'.$this->route($size, $size, true).'" alt="'.$this->alt.'" title="'.$this->title.'" class="img-rounded"/>';
        } else {
            $return .= '<div style="display:table;width:100%"><div style="display:table-cell;vertical-align:middle;width:'.$size.'px;height:'.$size.'px"><i class="fi fi-'.$this->extension.' fa-4x"></i></div></div>';
        }
        $return .= '</a>';
        return '<div class="text-center">'.$return.'</div>';
    }

    public function displayAdminStatus()
    {
        return displayAdminStatus($this->status);
    }

    public function displayAdminCategories()
    {
        $categories = '';
        foreach ($this->categories as $key => $c) {
            $categories .= '<li'.($key < $this->categories->count() - 1 ? ' style="margin-bottom:5px"' : '').'><span class="label label-info">'.$c->name.'</span></li>';
        }
        return strlen($categories) ? '<ul style="margin-bottom:0px;list-style-type:none;padding:0 10px;">'.$categories.'</ul>' : '-';
    }

    public function displayAdminDates()
    {
        $dates = '<ul style="margin-bottom:0;list-style-type:none;padding-left:0;width:140px;display:inline-block">
                    <li><i class="fa fa-cloud-upload" data-toggle="tooltip" title="Date d\'upload"></i>&nbsp;&nbsp;'.format_datetime($this->created_at).'</li>';
        if ($this->created_at != $this->updated_at) {
            $dates .= '<li><i class="fa fa-pencil-square-o" data-toggle="tooltip" title="Date de modification"></i>&nbsp;&nbsp;'.format_datetime($this->updated_at).'</li>';
        }
        $dates .= '</ul>';
        return $dates;
    }

    public function displayAdminLanguage()
    {
        $language = '';
        if ($this->language) {
            $language = $this->language;
            if ($language == 'en') {
                $language = 'gb';
            }
            $language = '<span class="flag-icon flag-icon-'.$language.'" data-toggle="tooltip" title="'.self::languages()[$this->language].'" style="width:30px;height:20px;"></span>';
        }
        return '<div class="text-center">'.(strlen($language) ? $language : '-').'</div>';
    }

    public function formattedOriginalName()
    {
        return str_slug(pathinfo($this->original_name, PATHINFO_FILENAME)).'.'.$this->extension;
    }

    public function downloadIcon()
    {
        return asset('images/ico-'.($this->isVideo() ? 'video' : 'download').'.png');
    }

    public function isVideo()
    {
        return in_array($this->extension, videosExtensions());
    }

    public function cryptedKey()
    {
        return Crypt::encrypt($this->id.$this->created_at);
    }

    public function form()
    {
        $form = null;
        $type = $this->types()->where('type', 'App\Models\FormU')->first();
        if ($type) {
            $form = FormU::find($type->type_id);
        }
        return $form;
    }

    public function hrefLink()
    {
        $form = $this->form();
        $attributes = '';
        if ($form) {
            $link = '#';
            $attributes = ' class="show-form-modal" data-id="'.$form->id.'"';
        } else {
            $link = $this->route();
        }
        return 'href="'.$link.'"'.$attributes;
    }

    public function deleteMedia()
    {
        $cache_path = config('paths.cache').$this->id;
        if (is_dir($cache_path)) {
            deleteDir($cache_path);
        }
        $path_to_delete = config('paths.medias').$this->name;
        if (file_exists($path_to_delete)) {
            if (is_dir($path_to_delete)) {
                deleteDir($path_to_delete);
            } else {
                unlink($path_to_delete);
            }
        }
        $this->delete();
        return;
    }
}
