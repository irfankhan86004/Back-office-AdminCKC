<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;

use App\Models\Carousel;
use App\Models\CarouselPage;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageHistory;
use App\Models\PageLang;
use App\Models\RedirectU;
use Image;
use File;
use Form;
use DB;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:4');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $pages = Page::select('pages.*')
                            ->join('pages_lang', 'pages.id', '=', 'pages_lang.page_id')
                            ->leftJoin('admins', 'pages.admin_id', '=', 'admins.id');

            $order_array = [
                0   => 'pages.id',
                1   => 'pages_lang.name',
                2 	=> 'pages_lang.url',
                3 	=> 'pages.published',
                4   => 'pages.created_at',
                5 	=> 'admins.first_name',
            ];

            if ($search!='') {
                $pages = $pages->where(function ($query) use ($search) {
                    $query->where('pages.id', 'LIKE', '%'.$search.'%')
                          ->orWhere('pages.created_at', 'LIKE', '%'.date_to_mysql($search).'%')
                          ->orWhere('pages_lang.name', 'LIKE', '%'.$search.'%')
                          ->orWhere('pages_lang.url', 'LIKE', '%'.$search.'%')
                          ->orWhere(DB::raw('CONCAT(admins.last_name," ",admins.first_name)'), 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $pages = $pages->orderBy($order_array[$order['column']], $order['dir']);
            }

            $pages_total = $pages->count('pages.id');
            $pages = $pages->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($pages);
            $recordsFiltered = $pages_total;
            $data = [];
            $i = 0;
            foreach ($pages as $page) {
                $data[$i][] = 	'<div class="text-center">'.$page->id.'</div>';
                $data[$i][] = 	'<a href="'.route('pages.edit', array($page->id)).'">'.ucfirst($page->getAttr('name')).'</a>';
                $data[$i][] = 	$page->getAttr('url');
                $data[$i][] = 	'<div class="text-center">'.($page->published ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-times fa-2x text-danger"></i>').'</div>';
                $data[$i][] = 	format_date($page->created_at);
                $data[$i][] = 	$page->admin ? $page->admin->displayName() : '-';
                $data[$i][] = 	'<div class="text-right">
                					'.Form::open(['method' => 'DELETE', 'route' => ['pages.destroy', $page->id], 'id'=>'delete-'.$page->id]).'
		            				<div class="btn-group btn-group-sm">
		                    			<a href="'.route('pages.edit', array($page->id)).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
		                    			<a href="'.route('page_duplicate', array($page->id)).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Duplicate"><i class="fa fa-copy"></i></a>
		                    			<a href="#" type="submit" class="btn btn-danger delete" data-entry="'.$page->id.'" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
									</div>
									'.Form::close().'
								</div>';
                $i++;
            }

            $results = [
                'draw'				=> $request->draw,
                'recordsTotal' 		=> $recordsTotal,
                'recordsFiltered' 	=> $recordsFiltered,
                'data' 				=> $data
            ];

            return json_encode($results);
            die();
        }
    }

    public function order_ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $results = [];
            $pages = Page::orderBy('position', 'ASC')->get();

            if (count($pages)>=1) {
                foreach ($pages as $p) {
                    $results[] = 	'<div class="block animation-fadeInQuick" data-position="'.$p->position.'" data-id="'.$p->id.'" style="cursor:move;">
									    <div class="block-title" style="border:none;"></div>
									    <div class="row">
									    	<div class="col-md-3 text-center"><p><i class="fa fa-arrows"></i> '.$p->position.'</h2></p></div>
									    	<div class="col-md-3 text-center"><p><a href="'. route('pages.edit', array($p->id)) .'"><b>'.$p->id.'</b> - '. ucfirst($p->getAttr('name')) .'</a></p></div>
									    	<div class="col-md-3 text-center"><p><b>Url</b> : '.$p->getAttr('url').'</p></div>
									    	<div class="col-md-3 text-center"><p><span class="label label-'.($p->published==1 ? 'success' : 'danger').'">'.($p->published==1 ? 'Activée' : 'Désactivée').'</span></p></div>
									    </div>
									</div>';
                }
            }
            $data['results'] = $results;

            return json_encode($data);
            die();
        }
    }

    public function index()
    {
        $pages = Page::count();
        return view('admin.pages.index', compact('pages'));
    }

    public function getorder()
    {
        $pages = Page::count();
        return view('admin.pages.order', compact('pages'));
    }

    public function setorderpages(Request $request)
    {
        if ($request->ajax()) {
            if (! $page = Page::find($request->page_id)) {
                return 'error';
            }
            $page->update(['position' => $request->position]);
        }
    }

    public function edit(Page $page)
    {
        $languages = Language::all();

        // For Form::model
        $page_arr = [];
        $page_arr['id'] = $page->id;
        $page_arr['position'] = $page->position;
        $page_arr['published'] = $page->published;
        $page_arr['footer'] = $page->footer;

        // Langues
        foreach ($page->lang as $pl) {
            $page_arr['url_'.$pl->language->short] = $pl->url;
            $page_arr['canonical_url_'.$pl->language->short] = $pl->canonical_url;
            $page_arr['title_'.$pl->language->short] = $pl->title;
            $page_arr['name_'.$pl->language->short] = $pl->name;
            $page_arr['subname_'.$pl->language->short] = $pl->subname;
            $page_arr['keywords_'.$pl->language->short] = $pl->keywords;
            $page_arr['description_'.$pl->language->short] = $pl->description;
            $page_arr['text_'.$pl->language->short] = $pl->text;
        }

        // Uploads partials
        $model 		= 'page';
        $model_id 	= $page->id;

        return view('admin.pages.edit', compact('page', 'languages', 'page_arr', 'model', 'model_id'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->all());
        $this->input_checkbox($request, $page, 'published');
        $this->input_checkbox($request, $page, 'footer');

        // Langues
        foreach (Language::all() as $l) {
            $input_lang = [
                'page_id' => $page->id,
                'language_id' => $l->id,
                'name' => $request->input('name_'.$l->short),
                'subname' => $request->input('subname_'.$l->short),
                'url' => $request->input('url_'.$l->short),
                'canonical_url' => $request->input('canonical_url_'.$l->short),
                'title' => $request->input('title_'.$l->short),
                'keywords' => $request->input('keywords_'.$l->short),
                'description' => $request->input('description_'.$l->short),
            ];

            $page_lang = PageLang::where('page_id', $page->id)->where('language_id', $l->id)->first();
            if (!$page_lang) {
                PageLang::create($input_lang);
            } else {
                $page_lang->update($input_lang);
            }
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'Page <b>'.$page->getAttr('name').'</b> mise à jour']);
        return redirect()->route('pages.index');
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(PageRequest $request)
    {
        $request->merge([
            'position' => Page::count() ? Page::orderBy('position', 'DESC')->first()->position + 1 : 1,
            'admin_id' => auth()->guard('admin')->user()->id,
        ]);
        $page = Page::create($request->all());

        // Langues
        foreach (Language::all() as $language) {
            $input_lang = [
                'page_id' => $page->id,
                'language_id' => $language->id,
                'name' => $request->input('name_'.$language->short),
                'subname' => $request->input('subname_'.$language->short),
                'url' => $request->input('url_'.$language->short),
                'canonical_url' => $request->input('canonical_url_'.$language->short),
                'title' => $request->input('title_'.$language->short),
                'keywords' => $request->input('keywords_'.$language->short),
                'description' => $request->input('description_'.$language->short),
            ];
            PageLang::create($input_lang);
        }

        if ($request->input('upload_files') == 1) {
            return redirect()->route('pages.edit', [$page->id]);
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'La page <b>'.ucfirst($page->getAttr('name')).'</b> est créée avec succès']);
        return redirect()->route('pages.index');
    }

	public function edition(Page $page, Language $language)
	{
		$page_lang = PageLang::where('page_id', $page->id)->where('language_id', $language->id)->first();
		if (!$page_lang) {
			abort(404);
		}

		$content = $page_lang->text;

		if (isset($_GET['page_id'])) {
			$page_lang = PageLang::where('page_id', $_GET['page_id'])->where('language_id', $language->id)->first();
			if ($page_lang) {
				$content = $page_lang->text;
			}
		}

		// Uploads partials
		$model = 'page';
		$model_id = $page->id;

		// Other page lang
		$otherLang = $page->lang()->where('language_id', '!=', $language->id)->whereNotNull('text')->get();

		return view('admin.pages.edition', compact('page', 'language', 'content', 'model', 'model_id', 'otherLang'));
	}

	public function replaceLang(Page $page, Language $language, Language $sourceLang)
	{
		$page_lang = PageLang::where('page_id', $page->id)->where('language_id', $language->id)->first();
		$page_source = PageLang::where('page_id', $page->id)->where('language_id', $sourceLang->id)->first();

		if (!$page_lang || !$page_source) {
			abort(404);
		}

		$page_lang->text = $page_source->text;
		$page_lang->save();

		return redirect()->route('page_edition', [$page, $language]);
	}

	public function pagePreview(Page $page)
	{
		$html = request('html');
		$title = 'Prévisualisation - Back-office ' . config('app.name');
		$description = '';

		return response()->view('admin.pages.preview', compact('title', 'description', 'html', 'page'));
	}

    public function save_edition(Request $request)
    {
        if ($request->ajax()) {
            $page_lang = PageLang::where('page_id', $request->input('page_id'))->where('language_id', $request->input('language_id'))->first();
            if (!$page_lang) {
                return 'error';
            }
            $before = $page_lang->text;
            $text = trim($request->input('text'));
            $text = $text == '' ? null : str_replace(url()->to('/'), '', $text);
            $page_lang->update(['text' => $text]);
            $after = $page_lang->text;
            PageHistory::create([
                'page_id' => $page_lang->page->id,
                'language_id' => 1,
                'before' => $before,
                'after' => $after,
                'admin_id' => auth()->guard('admin')->user()->id,
            ]);
            return 'ok';
        }
        return 'error';
    }

    public function duplicate(Page $page)
    {
        $page_clone = $page->replicate();
        $page_clone->push();
        foreach ($page->lang as $page_lang) {
            $page_lang_clone = $page_lang->replicate();
            $page_lang_clone->page_id = $page_clone->id;
            $page_lang_clone->url = $page_clone->id.'-'.$page_lang->url;
            $page_lang_clone->push();
        }
        session()->flash('notification', ['type'=>'success', 'text'=>'La page a été dupliquée avec succès']);
        return redirect()->route('pages.index');
    }

    public function create_redirect(Request $request, Page $page)
    {
        if ($request->ajax()) {
            $request->merge([
                'page_id' => $page->id,
            ]);
            RedirectU::create($request->all());
            return;
        }
    }

    public function history_ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $history = PageHistory::join('admins', 'pages_history.admin_id', '=', 'admins.id');

            $order_array = [
                2 => 'pages_history.created_at',
                3 => 'admins.last_name',
            ];

            if ($search!='') {
                $history = $history->where(function ($query) use ($search) {
                    $query->where('pages_history.before', 'LIKE', '%'.$search.'%')
                          ->orWhere('pages_history.after', 'LIKE', '%'.$search.'%')
                          ->orWhere('pages_history.created_at', 'LIKE', '%'.date_to_mysql($search).'%')
                          ->orWhere(DB::raw('CONCAT(admins.last_name," ",admins.first_name)'), 'LIKE', '%'.$search.'%')
                          ->orWhere(DB::raw('CONCAT(admins.first_name," ",admins.last_name)'), 'LIKE', '%'.$search.'%');
                });
            }

            if (null !== $request->page_id) {
                $history = $history->where('pages_history.page_id', $request->page_id);
            }

            // Order
            foreach ($request->order as $order) {
                $history = $history->orderBy($order_array[$order['column']], $order['dir']);
            }

            $history_total = $history->count('pages_history.id');

            $history = $history->skip($request->start)->take($request->length)->get(['pages_history.id']);

            $recordsTotal = count($history);
            $recordsFiltered = $history_total;
            $data = [];
            $i = 0;

            foreach ($history as $hist) {
                $h = PageHistory::find($hist->id);
                $data[$i][] = 	'<div class="text-center">
	                				<a href="#" class="btn btn-info btn-sm" data-toggle="tooltip" title="Visualiser cette version">
	                					<i class="fa fa-rotate-left"></i>
	                				</a>
	                				<a href="#" class="btn btn-warning btn-sm restore-history" data-history-id="'.$h->id.'" data-type="before" data-toggle="tooltip" title="Restaurer cette version">
	                					<i class="fa fa-save"></i>
	                				</a>
                				</div>';
                $data[$i][] = 	'<div class="text-center">
	                				<a href="#" class="btn btn-info btn-sm" data-toggle="tooltip" title="Visualiser cette version">
	                					<i class="fa fa-rotate-right"></i>
	                				</a>
	                				<a href="#" class="btn btn-warning btn-sm restore-history" data-history-id="'.$h->id.'" data-type="after" data-toggle="tooltip" title="Restaurer cette version">
	                					<i class="fa fa-save"></i>
	                				</a>
	                			</div>';
                $data[$i][] = 	format_date($h->created_at);
                $data[$i][] = 	$h->admin->displayName();
                $i++;
            }

            $results = [
                'draw'				=> $request->draw,
                'recordsTotal' 		=> $recordsTotal,
                'recordsFiltered' 	=> $recordsFiltered,
                'data' 				=> $data
            ];

            return json_encode($results);
            die();
        }
    }

    public function restore_history(Request $request)
    {
        $status = false;
        if ($request->ajax()) {
            $history = PageHistory::find($request->history_id);
            if ($history && null !== $request->type && in_array($request->type, ['before', 'after'])) {
                $page_lang = PageLang::where('language_id', 1)->where('page_id', $history->page_id)->first();
                if ($page_lang) {
                    $page_lang->update(['text' => $history->{$request->type}]);
                    $status = true;
                }
            }
        }
        return json_encode(['status' => $status]);
    }



    public function carousels_position(Request $request)
    {
        if ($request->ajax()) {
            $position = 1;
            foreach ($request->carousels as $c) {
                $carousel = CarouselPage::find($c);
                if ($carousel) {
                    $carousel->update(['position' => $position]);
                    $position++;
                }
            }
        }
    }

    public function destroy(Page $page)
    {
        $page->delete();

        session()->flash('notification', ['type'=>'error', 'text'=>'Page supprimée']);
        return redirect()->route('pages.index');
    }

    private function input_checkbox(Request $request, Page $page, $field)
    {
        if (! isset($request->$field)) {
            $page->$field = 0;
            $page->save();
        }
    }
}
