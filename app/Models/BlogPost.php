<?php

namespace App\Models;

use App\Traits\MorphManyMedias;

use Carbon\Carbon;

class BlogPost extends LangModel
{
    use MorphManyMedias;

    protected $table = 'blog_posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['blog_category_id', 'published', 'featured', 'date', 'heure', 'date_hide', 'written_by', 'admin_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function categories()
    {
        return $this->belongsToMany('App\Models\BlogCategory', 'blog_posts_categories', 'blog_post_id', 'blog_category_id');
    }

    public function lang()
    {
        return $this->hasMany('App\Models\BlogPostLang');
    }

    public function getAttr($attribut)
    {
        return $this->getLangAttr($attribut);
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date_to_mysql($value);
    }

    public function getDateAttribute($value)
    {
        return format_date($value);
    }

    public function url()
    {
        return route('post.show', [
            'url' => str_slug($this->getAttr('url')),
        ]);
    }

    public static function create_select()
    {
        $select = [];
        foreach (BlogPostLang::orderBy('name', 'ASC')->get() as $bpl) {
            if ($bpl->article) {
                $select[$bpl->article->id] = $bpl->article->getAttr('name');
            }
        }
        return $select;
    }

    public function displayAdminCategories()
    {
        $categories = '';
        foreach ($this->categories as $key => $c) {
            $categories .= '<li'.($key < $this->categories->count() - 1 ? ' style="margin-bottom:5px"' : '').'><span class="label label-info">'.$c->getAttr('name').'</span></li>';
        }
        return strlen($categories) ? '<ul style="margin-bottom:0px">'.$categories.'</ul>' : '-';
    }

    public function displayDate()
    {
        return Carbon::createFromFormat('d/m/Y', $this->date)->formatLocalized('%A %d %B %Y');
    }

    public function getShortText($lenght = 100)
    {
        if (strlen($this->getAttr('text')) > $lenght) {
            $end = strlen($this->getAttr('text')) > $lenght ? '...' : '' ;
            $pos = strpos($this->getAttr('text'), ' ', $lenght);

            return substr($this->getAttr('text'), 0, $pos ? $pos : $lenght) . $end;
        }
        return $this->getAttr('text');
    }

    public function getPicture($width = 150, $height = 150)
    {
        if (!empty($this->getMedias()[0])) {
            return $this->getMedias()[0]->route($width, $height);
        } else {
            return 'https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . config('app.name');
        }
    }
}
