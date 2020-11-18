<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MediaRequest;

use App\Models\BlogPostLang;
use App\Models\Media;
use App\Models\MediaCategory;
use App\Models\MediaLink;
use App\Models\MediaType;
use App\Models\PageLang;
use App\Models\RedirectU;
use App\Models\UrlU;
use Form;
use Image;
use DB;
use File;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:6');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $medias = Media::leftJoin('medias_medias_categories', 'medias_medias_categories.media_id', '=', 'medias.id')
                           ->leftJoin('medias_categories', 'medias_medias_categories.media_category_id', '=', 'medias_categories.id')
                           ->leftJoin('admins', 'medias.admin_id', '=', 'admins.id');

            if (null !== $request->search_columns && count($request->search_columns)) {
                foreach ($request->search_columns as $column => $value) {
                    $value = trim($value);
                    if ($value != '') {
                        if ($column == 'admin') {
                            $medias = $medias->where(function ($query) use ($value) {
                                $query = $query->where(DB::raw('CONCAT(admins.last_name," ",admins.first_name)'), 'LIKE', '%'.$value.'%')
                                               ->orWhere(DB::raw('CONCAT(admins.first_name," ",admins.last_name)'), 'LIKE', '%'.$value.'%');
                            });
                        } elseif ($column == 'media_category') {
                            $medias = $medias->whereHas('categories', function ($query) use ($value) {
                                $query = $query->where('medias_categories.name', 'LIKE', '%'.$value.'%');
                            });
                        } elseif ($column == 'dates') {
                            $medias = $medias->where(function ($query) use ($value) {
                                $query = $query->where(DB::raw('SUBSTR(medias.created_at, 1, 10)'), date_to_mysql($value))
                                               ->orWhere(DB::raw('SUBSTR(medias.updated_at, 1, 10)'), date_to_mysql($value));
                            });
                        } elseif ($column == 'public') {
                            if ($value != 'all') {
                                $medias = $medias->where('medias.public', $value == 'public');
                            }
                        } else {
                            $medias = $medias->where('medias.'.$column, 'LIKE', '%'.$value.'%');
                        }
                    }
                }
            }

            if (null !== $request->min_created_at && trim($request->min_created_at) != '') {
                $medias = $medias->where(DB::raw('SUBSTR(medias.created_at, 1, 10)'), '>=', date_to_mysql(trim($request->min_created_at)));
            }

            if (null !== $request->max_created_at && trim($request->max_created_at) != '') {
                $medias = $medias->where(DB::raw('SUBSTR(medias.created_at, 1, 10)'), '<=', date_to_mysql(trim($request->max_created_at)));
            }

            if (null !== $request->validation && $request->validation == 1) {
                $medias = $medias->submitted();
                if (!auth()->guard('admin')->user()->mediasAdmin()) {
                    $medias = $medias->whereIn('medias.admin_id', auth()->guard('admin')->user()->accessibleAdmins());
                }
            }

            if ($request->showCheckboxes) {
                $order_array = [
                    0   => 'medias.id',
                    3   => 'medias.media_name',
                    4   => 'medias.type',
                    5   => 'medias.language',
                    8   => 'admins.last_name',
                    9   => 'medias.status',
                    10  => 'medias.public',
                ];
            } else {
                $order_array = [
                    0   => 'medias.id',
                    2   => 'medias.media_name',
                    3   => 'medias.type',
                    4   => 'medias.language',
                    7   => 'admins.last_name',
                    8   => 'medias.status',
                    9   => 'medias.public',
                ];
            }

            if ($search!='') {
                $medias = $medias->where(function ($query) use ($search) {
                    $query->where('medias.id', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.name', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.original_name', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.media_name', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.size', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.extension', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias_categories.name', 'LIKE', '%'.$search.'%')
                          ->orWhere('medias.created_at', 'LIKE', '%'.date_to_mysql($search).'%')
                          ->orWhere('medias.updated_at', 'LIKE', '%'.date_to_mysql($search).'%')
                          ->orWhere(DB::raw('CONCAT(admins.last_name," ",admins.first_name)'), 'LIKE', '%'.$search.'%')
                          ->orWhere(DB::raw('CONCAT(admins.first_name," ",admins.last_name)'), 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $medias = $medias->orderBy($order_array[$order['column']], $order['dir']);
            }

            $medias_total = $medias->count('medias.id');
            if ($request->length != -1) {
                $medias = $medias->skip($request->start)->take($request->length);
            }
            $medias = $medias->get(['medias.id']);

            $recordsTotal = count($medias);
            $recordsFiltered = $medias_total;
            $data = [];
            $i = 0;
            foreach ($medias as $media) {
                $m = Media::find($media->id);
                $data[$i][] =   '<div class="text-center">'.$m->id.'</div>';
                if ($request->showCheckboxes) {
                    $data[$i][] = '<div class="text-center"><input type="checkbox" name="media_id" value="'.$m->id.'"/></div>';
                }
                $data[$i][] =   $m->adminListingPreview();
                $data[$i][] =   $m->media_name;
                $data[$i][] =   '<div class="text-center" style="font-size:18px">'.($m->type == 'picture' ? '<i class="fa fa-file-image-o text-muted" data-toggle="tooltip" title="Image"></i>' : '<i class="fa fa-file-o text-muted" data-toggle="tooltip" title="File"></i>').'</div>';
                $data[$i][] =   $m->displayAdminLanguage();
                $data[$i][] =   $m->displayAdminCategories();
                $data[$i][] =   '<div class="text-center">'.$m->displayAdminDates().'</div>';
                $data[$i][] =   $m->admin ? $m->admin->displayName() : '-';
                $data[$i][] =   '<div class="text-center">'.$m->displayAdminStatus().'</div>';
                $data[$i][] =   '<div class="text-center">'.($m->public ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-times fa-2x text-danger"></i>').'</div>';
                if ($request->showActions) {
                    if (auth()->guard('admin')->user()->canAccessMedia($m)) {
                        $last_column =  Form::open(['method' => 'DELETE', 'route' => ['medias.destroy', $m->id], 'id' => 'delete-'.$m->id]).'
                                            <div class="btn-group btn-group-sm">
                                                <a href="'.route('medias.edit', array($m->id)).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>'.

                                                (auth()->guard('admin')->user()->canDeleteMedia($m) ? '<a href="#" type="submit" class="btn btn-danger delete" data-entry="'.$m->id.'" data-name="'.$m->media_name.'" data-toggle="tooltip" title="Remove"><i class="gi gi-remove_2"></i></a>' : '')

                                            .'</div>'.
                                        Form::close();
                    } else {
                        $last_column = '-';
                    }
                } else {
                    if ($m->public || ($request->model != 'page' && $request->model != 'blog_post')) {
                        $last_column =  '<label class="switch switch-primary">
                                            '.Form::checkbox('media_type', 1, $media->hasType($request->model_id, $request->model), ['data-id' => $m->id, 'data-type-id' => $request->model_id, 'data-type' => $request->model]).'
                                            <span></span>
                                        </label>';
                    } else {
                        $last_column = '-';
                    }
                }
                $data[$i][] = '<div class="text-center">'.$last_column.'</div>';
                $i++;
            }

            $results = [
                'draw'              => $request->draw,
                'recordsTotal'      => $recordsTotal,
                'recordsFiltered'   => $recordsFiltered,
                'data'              => $data
            ];

            return json_encode($results);
            die();
        }
    }

    public function index()
    {
        $medias = Media::count();
        $hide_medias_listing = true;
        $update_medias_listing = true;
        $show_actions = true;
        $show_btn_upload_scroll = true;
        $show_checkboxes = true;
        return view('admin.medias.index', compact('medias', 'hide_medias_listing', 'update_medias_listing', 'show_actions', 'show_btn_upload_scroll', 'show_checkboxes'));
    }

    public function edit(Media $media)
    {
        if (!auth()->guard('admin')->user()->canAccessMedia($media)) {
            session()->flash('notification', ['type' => 'warning', 'text' => 'Vous n\'avez pas la permission d\'accéder à ce média']);
            return redirect()->route('medias.index');
        }

        $media_route = $media->route();
        $categories = MediaCategory::create_select(false);
        $languages = Media::languages();
        if ($media->type == 'file') {
            unset($languages['']);
        }
        return view('admin.medias.edit', compact('media', 'media_route', 'categories', 'languages'));
    }

    public function update(MediaRequest $request, Media $media)
    {
        $media->update($request->all());
        $this->input_checkbox($request, $media, 'flip_x');
        $this->input_checkbox($request, $media, 'flip_y');
        $this->input_checkbox($request, $media, 'public');
        $this->syncCategories($media, $request->categories);
        $this->syncLinks($media, $request->links);

        if (!$media->public) {
            foreach ($media->types()->where('type', 'App\Models\Page')->get() as $t) {
                $t->delete();
            }
        }

        $cache_path = config('paths.cache').$media->id;
        if (is_dir($cache_path)) {
            deleteDir($cache_path);
        }

        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $extension = $request->file('file')->getClientOriginalExtension();
                if (
                    ($media->type == 'file' && !in_array(trim(strtolower($extension)), picturesExtensions()))
                    ||
                    ($media->type == 'picture' && in_array(trim(strtolower($extension)), picturesExtensions()))
                ) {
                    $path_to_delete = config('paths.medias').$media->name;
                    $original_filename = $request->file('file')->getClientOriginalName();
                    $filename = uniqid().'-'.str_slug($original_filename);
                    $path = config('paths.medias').$filename;
                    $is_picture = false;
                    if ($media->type == 'file') {
                        $new_media = $request->file('file')->move(config('paths.medias'), $filename);
                    } elseif ($media->type == 'picture') {
                        $is_picture = true;
                        $new_media = Image::make($request->file('file'))->orientate()->save($path);
                    }
                    if ($new_media) {
                        $size = $is_picture ? $new_media->filesize() : $request->file('file')->getClientSize();
                        $md5 = md5_file($path);
                        if ($check_media = Media::where('size', $size)->where('md5', $md5)->first()) {
                            unlink($path);
                            session()->flash('notification', ['type' => 'error', 'text' => 'Ce média existe déjà en base de donnée (#'.$check_media->id.' - '.$check_media->media_name.')']);
                            return redirect()->back();
                        } else {
                            if (file_exists($path_to_delete)) {
                                if (is_dir($path_to_delete)) {
                                    deleteDir($path_to_delete);
                                } else {
                                    unlink($path_to_delete);
                                }
                            }
                            $old_route = str_replace(url()->to('/'), '', $media->route());
                            $media->update([
                                'name' => $filename,
                                'original_name' => $original_filename,
                                'size' => $size,
                                'md5' => $md5,
                                'resolution' => $is_picture ? $new_media->width().'x'.$new_media->height() : null,
                                'extension' => $extension,
                            ]);
                            $media = Media::find($media->id);
                            $new_route = str_replace(url()->to('/'), '', $media->route());
                            if ($media->type == 'file' && $old_route != $new_route && $media->status == 'published' && env('APP_ENV') == 'production') {
                                $checkRedirect = RedirectU::where('origin_url', $old_route)->first();
                                if (!$checkRedirect) {
                                    $url = UrlU::where('link', $new_route)->first();
                                    if (!$url) {
                                        $url = UrlU::create([
                                            'link' => $new_route,
                                            'target' => '_self',
                                        ]);
                                    }
                                    RedirectU::create([
                                        'active' => 1,
                                        'type' => 'permanent',
                                        'origin_url' => $old_route,
                                        'url_id' => $url->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($media->type == 'picture' && ($media->flip_x==1 || $media->flip_y==1)) {
            $media_path = config('paths.medias').$media->name;
            if (file_exists($media_path)) {
                $img = Image::make($media_path);
                if ($media->flip_x==1) {
                    $img->flip('v');
                } elseif ($media->flip_y==1) {
                    $img->flip('h');
                }
                $img->save($media_path);
            }
        }

        session()->flash('notification', ['type' => 'success', 'text' => 'Le média à été mis à jour avec succès']);
        return redirect()->route('medias.index');
    }

    public function destroy(Media $media)
    {
        $media->deleteMedia();
        session()->flash('notification', ['type' => 'error', 'text' => 'Média supprimé']);
        return redirect()->route('medias.index');
    }

    public function upload(Request $request)
    {
        if ($request->ajax()) {
            $status = false;
            $msg = null;
            $color = null;
            if ($request->hasFile('file')) {
                if ($request->file('file')->isValid()) {
                    $original_filename = $request->file('file')->getClientOriginalName();
                    $original_filename_without_extension = pathinfo($original_filename, PATHINFO_FILENAME);
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $filename = uniqid().'-'.str_slug($original_filename_without_extension) . '.' . $extension;
                    $path = config('paths.medias').$filename;
                    $type = getMediaType($extension);
                    $new_media = null;
                    $is_picture = false;
                    if ($type == 'file' || $type == 'video') {
                        $new_media = $request->file('file')->move(config('paths.medias'), $filename);
                    } elseif ($type == 'picture') {
                        $new_media = Image::make($request->file('file'))->orientate()->save($path);
                        $is_picture = true;
                    }
                    if ($new_media) {
                        $size = $is_picture ? $new_media->filesize() : File::size(config('paths.medias').$filename);
                        $md5 = md5_file($path);
                        if ($check_media = Media::where('size', $size)->where('md5', $md5)->first()) {
                            unlink($path);
                            $msg = 'Le fichier existe déjà en base de données (#'.$check_media->id.' - '.$check_media->media_name.')';
                            $color = 'warning';
                        } else {
                            $media = Media::create([
                                'name' => $filename,
                                'original_name' => $original_filename,
                                'media_name' => $original_filename,
                                'size' => $size,
                                'md5' => $md5,
                                'resolution' => $is_picture ? $new_media->width().'x'.$new_media->height() : null,
                                'extension' => $extension,
                                'alt' => $original_filename_without_extension,
                                'title' => $original_filename_without_extension,
                                'type' => $type,
                                'admin_id' => auth()->guard('admin')->user()->id,
                                'status' => auth()->guard('admin')->user()->defaultMediaStatus(),
                                'public' => 1,
                            ]);
                            if (null !== $request->model && null !== $request->model_id) {
                                $class = getClass($request->model);
                                $id = $request->model_id;
                                $model = $class::find($id);
                                if ($model) {
                                    MediaType::create([
                                        'media_id' => $media->id,
                                        'type_id' => $id,
                                        'type' => $class,
                                        'position' => $model->medias->count() + 1,
                                    ]);
                                }
                            }
                            $status = true;
                        }
                    }
                }
            }
            return json_encode([
                'status' => $status,
                'msg' => $msg,
                'color' => $color,
            ]);
        }
    }

    public function ajax_listing_partial(Request $request)
    {
        if ($request->ajax()) {
            $content = '';
            $data['result'] = false;
            $data['msg'] = 'An error occurred to display the listing, please reload the page';

            $class = getClass($request->model);
            $id = $request->model_id;

            if (! $model = $class::find($id)) {
                return json_encode($data);
            }

            $medias = $model->medias;

            if (count($medias)>=1) {
                $content .= '<table class="table table-bordered table-striped table-vcenter">
                                <tbody class="sortable_files">';
                foreach ($medias as $m) {
                    $media = $m->media;
                    $downloads_requests_infos = '';
                    if ($request->model == 'formU') {
                        $downloads_requests_infos = $media->downloadsRequestsInfos();
                    }
                    $content .=     '<tr data-position="'.$m->position.'" data-id="'.$m->id.'" style="cursor:move;">
                                        <td class="text-center" style="width:20%;">'.$media->adminListingPreview().'</td>
                                        <td class="text-center">
                                            '.$media->media_name.' '.($downloads_requests_infos != '' ? '&nbsp;<i class="fa fa-info-circle" data-toggle="tooltip" data-html="true" title="'.$downloads_requests_infos.'"></i>&nbsp;&nbsp;' : '').'<a href="'.route('medias.edit', [$media->id]).'" class="btn btn-info btn-xs" target="_blank" style="margin-left:5px">Edit</a>
                                        </td>
                                        <td class="text-center">
                                            <div class="input-group">
                                                <input type="text" value="'.$media->route().'" id="media_type_id_'.$m->id.'" readonly="readonly" class="form-control text-center"/>
                                                <span class="input-group-addon clipboard-media-url" data-id="media_type_id_'.$m->id.'" data-toggle="tooltip" data-container="body" title="Copy the URL media to the clipboard" style="cursor:pointer">
                                                    <i class="fa fa-clipboard"></i>
                                                </span>
                                            </div>
                                        </td>';
                    if (auth()->guard('admin')->user()->canDeleteMedia($media)) {
                        $content .=     '<td class="text-center">
                                            <a href="#" class="btn btn-xs btn-danger remove_media" data-id="'.$m->id.'"><i class="fa fa-trash-o"></i> Remove</a>
                                        </td>';
                    }
                    $content .=     '</tr>';
                }
                $content .= '   </tbody>
                            </table>';
            }
            unset($data['msg']);
            $data['content'] = $content;
            $data['result'] = true;

            return json_encode($data);
        }
    }

    public function set_order(Request $request)
    {
        if ($request->ajax()) {
            if (null !== $request->media_types) {
                $position = 1;
                foreach ($request->media_types as $mt) {
                    $media_type = MediaType::find($mt);
                    if ($media_type) {
                        $media_type->update(['position' => $position]);
                        $position++;
                    }
                }
            }
        }
    }

    public function remove(Request $request)
    {
        if ($request->ajax()) {
            $data['result'] = false;
            $media_type = MediaType::find($request->media_type_id);
            if ($media_type) {
                $class = $media_type->type;
                $object = $class::find($media_type->type_id);
                $media_type->delete();
                $data['result'] = true;
                if ($object) {
                    $position = 1;
                    foreach ($object->getMediasTypes() as $media_type) {
                        $media_type->update(['position' => $position]);
                        $position++;
                    }
                }
            }
            return json_encode($data);
        }
    }

    public function affectation(Request $request)
    {
        if ($request->ajax()) {
            $media_type_array = [
                'media_id' => $request->media_id,
                'type_id' => $request->type_id,
                'type' => getClass($request->type),
            ];
            if ($request->checked) {
                MediaType::firstOrCreate($media_type_array);
            } else {
                $media_type = MediaType::firstOrNew($media_type_array);
                if ($media_type) {
                    $media_type->delete();
                }
            }
        }
    }

    public function autocomplete(Request $request)
    {
        if ($request->ajax()) {
            $input = trim($request->q);
            $medias = Media::where(function ($q) use ($input) {
                $q = $q->where('name', 'LIKE', '%'.$input.'%')
                    ->orWhere('original_name', 'LIKE', '%'.$input.'%')
                    ->orWhere('media_name', 'LIKE', '%'.$input.'%');
            });
            if (null !== $request->media_id) {
                $medias = $medias->where('id', '!=', $request->media_id);
            }
            $medias = $medias->take(10)
                            ->orderBy('media_name', 'ASC')
                            ->get();
            $results = [];
            $has_results = false;
            foreach ($medias as $m) {
                $has_results = true;
                $results[] = [
                    'id' => $m->id,
                    'text' => '#'.$m->id.' - '.$m->media_name,
                ];
            }
            $return_results = [];
            if ($has_results) {
                $return_results['results'] = $results;
            }
            return json_encode($return_results);
        }
    }

    public function mulitple_actions(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->medias as $m) {
                $media = Media::find($m);
                if ($media) {
                    if ($request->action == 'status') {
                        $array_values = [];
                        if ($request->status != '') {
                            $array_values['status'] = $request->status;
                        }
                        if ($request->public != '') {
                            $array_values['public'] = $request->public;
                        }
                        $media->update($array_values);
                    } elseif ($request->action == 'delete') {
                        $media->deleteMedia();
                    }
                }
            }
            return Media::count();
        }
    }

    public function merge(Request $request)
    {
        if ($request->ajax() && $main_media = Media::find($request->media_id)) {
            foreach ($request->medias as $m) {
                $media = Media::find($m);
                if ($media) {
                    $picture_pattern = 'media-picture/'.$media->id;
                    $file_pattern = 'media-file/'.$media->id;
                    $pages_lang = PageLang::where(function ($q) use ($picture_pattern, $file_pattern) {
                        $q = $q->where('text', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('text', 'LIKE', '%'.$file_pattern.'%')
                            ->orWhere('highlight', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('highlight', 'LIKE', '%'.$file_pattern.'%')
                            ->orWhere('more_info', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('more_info', 'LIKE', '%'.$file_pattern.'%')
                            ->orWhere('gallery', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('gallery', 'LIKE', '%'.$file_pattern.'%')
                            ->orWhere('applications', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('applications', 'LIKE', '%'.$file_pattern.'%');
                    })->get();
                    foreach ($pages_lang as $p) {
                        $p->update([
                            'text' => $p->text ? $this->updateTextMedia($p->text, $media, $main_media) : null,
                            'highlight' => $p->highlight ? $this->updateTextMedia($p->highlight, $media, $main_media) : null,
                            'more_info' => $p->more_info ? $this->updateTextMedia($p->more_info, $media, $main_media) : null,
                            'gallery' => $p->gallery ? $this->updateTextMedia($p->gallery, $media, $main_media) : null,
                            'applications' => $p->applications ? $this->updateTextMedia($p->applications, $media, $main_media) : null,
                        ]);
                    }
                    $blog_posts_lang = BlogPostLang::where(function ($q) use ($picture_pattern, $file_pattern) {
                        $q = $q->where('text', 'LIKE', '%'.$picture_pattern.'%')
                            ->orWhere('text', 'LIKE', '%'.$file_pattern.'%');
                    })->get();
                    foreach ($blog_posts_lang as $b) {
                        $b->update(['text' => $b->text ? $this->updateTextMedia($b->text, $media, $main_media) : null]);
                    }
                    foreach ($media->types as $t) {
                        $t->update(['media_id' => $main_media->id]);
                    }
                    foreach ($media->link as $l) {
                        $t->update(['media_id_1' => $main_media->id]);
                    }
                    foreach ($media->linked as $l) {
                        $t->update(['media_id_2' => $main_media->id]);
                    }
                    foreach ($media->requests as $r) {
                        $r->update(['media_id' => $main_media->id]);
                    }
                    $media->deleteMedia();
                }
            }
            return json_encode(['status' => true]);
        }
    }

    public function export()
    {
        $medias = Media::latest()->get();
        header('Content-Type: text/csv');
        header('Content-disposition: attachment; filename=media.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Name', 'Type', 'Lang', 'Created at', 'Updated at', 'Author', 'Status', 'Public']);
        foreach ($medias as $m) {
            fputcsv($out, [
                $m->id,
                $m->media_name,
                ucfirst($m->type),
                $m->language,
                format_datetime($m->created_at),
                format_datetime($m->updated_at),
                $m->admin ? $m->admin->displayName() : '',
                ucfirst($m->status),
                $m->public ? 'Yes' : 'No',
            ]);
        }
        fclose($out);
    }

    public function find_missing_medias()
    {
        $file_pattern = 'media-file/';
        $columns = ['text', 'highlight', 'more_info', 'gallery', 'applications'];
        $pages_lang = PageLang::where(function ($q) use ($file_pattern, $columns) {
            foreach ($columns as $c) {
                $q = $q->orWhere($c, 'LIKE', '%'.$file_pattern.'%');
            }
        })->get();
        echo '<table border="1"><tr><td>Page ID</td><td>Media ID</td><td>Colonne</td></tr>';
        foreach ($pages_lang as $p) {
            foreach ($columns as $c) {
                if (preg_match_all('/'.preg_quote($file_pattern, '/').'(-?\d+)/', $p->{$c}, $files)) {
                    if (isset($files[1])) {
                        $medias_ids = $files[1];
                        foreach ($medias_ids as $m_id) {
                            $media = Media::find($m_id);
                            if ($media) {
                                $media_pattern = $file_pattern.$media->id.'-'.$media->formattedOriginalName();
                                if (strpos($p->{$c}, $media_pattern) === false) {
                                    echo '<tr><td>'.$p->page_id.'</td><td>'.$media->id.'</td><td>'.$c.'</td></tr>';
                                }
                            }
                        }
                    }
                }
            }
        }
        echo '</table>';
        echo '<br/><br/>';
        $posts_lang = BlogPostLang::where('text', 'LIKE', '%'.$file_pattern.'%')->get();
        echo '<table border="1"><tr><td>Post ID</td><td>Media ID</td></tr>';
        foreach ($posts_lang as $p) {
            if (preg_match_all('/'.preg_quote($file_pattern, '/').'(-?\d+)/', $p->text, $files)) {
                if (isset($files[1])) {
                    $medias_ids = $files[1];
                    foreach ($medias_ids as $m_id) {
                        $media = Media::find($m_id);
                        if ($media) {
                            $media_pattern = $file_pattern.$media->id.'-'.$media->formattedOriginalName();
                            if (strpos($p->text, $media_pattern) === false) {
                                echo '<tr><td>'.$p->blog_post_id.'</td><td>'.$media->id.'</td></tr>';
                            }
                        }
                    }
                }
            }
        }
        echo '</table>';
    }

    private function updateTextMedia($text, $old_media, $new_media)
    {
        $picture_pattern = 'media-picture/';
        $file_pattern = 'media-file/';
        $media_picture_pattern = $picture_pattern.$old_media->id;
        $media_file_pattern = $file_pattern.$old_media->id;
        $media_formatted_name = '-'.$old_media->formattedOriginalName();
        if ((strpos($text, $media_picture_pattern) !== false || strpos($text, $media_file_pattern) !== false) && strpos($text, $media_formatted_name) !== false) {
            $text = str_replace($media_picture_pattern, $picture_pattern.$new_media->id, $text);
            $text = str_replace($media_file_pattern, $file_pattern.$new_media->id, $text);
            $text = str_replace($media_formatted_name, '-'.$new_media->formattedOriginalName(), $text);
        }
        return $text;
    }

    private function input_checkbox(Request $request, Media $media, $field)
    {
        if (! isset($request->$field)) {
            $media->$field = 0;
            $media->save();
        }
    }

    private function syncCategories(Media $media, $categories)
    {
        if ($categories === null) {
            $categories = [];
        }
        $media->categories()->sync($categories);
    }

    private function syncLinks(Media $media, $links)
    {
        foreach ($media->links as $l) {
            $l->delete();
        }
        if (null !== $links) {
            foreach ($links as $l) {
                MediaLink::create([
                    'media_id_1' => $media->id,
                    'media_id_2' => $l,
                ]);
            }
        }
    }
}
