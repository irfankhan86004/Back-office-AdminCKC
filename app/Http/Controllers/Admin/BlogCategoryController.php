<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BlogCategoryRequest;

use App\Models\Language;
use App\Models\BlogCategory;
use App\Models\BlogCategoryLang;
use Form;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:2');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $categories = BlogCategory::select('blog_categories.*')
                                        ->join('blog_categories_lang', 'blog_categories_lang.blog_category_id', '=', 'blog_categories.id');

            $order_array = [
                0 	=> 'blog_categories.id',
                1 	=> 'blog_categories_lang.name',
                2 	=> 'blog_categories.position',
                4 	=> 'blog_categories.created_at'
            ];

            if ($search!='') {
                $categories = $categories->where(function ($query) use ($search) {
                    $query->where('blog_categories.id', 'LIKE', '%'.$search.'%')
                        ->orWhere('blog_categories_lang.name', 'LIKE', '%'.$search.'%')
                        ->orWhere('blog_categories.position', 'LIKE', '%'.$search.'%')
                        ->orWhere('blog_categories.created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $categories = $categories->orderBy($order_array[$order['column']], $order['dir']);
            }

            $categories_total = $categories->count('blog_categories.id');
            $categories = $categories->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($categories);
            $recordsFiltered = $categories_total;
            $data = [];
            $i = 0;

            foreach ($categories as $category) {
                $data[$i][] = 	'<div class="text-center">'.$category->id.'</div>';
                $data[$i][] = 	'<a href="'.route('categories-blog.edit', array($category->id)).'">'.ucfirst($category->getAttr('name')).'</a>';
                $data[$i][] = 	'<span class="label label-primary">'.$category->position.'</span>';
                $data[$i][] = 	'<span class="label label-primary">'.count($category->posts).'</span>';
                $data[$i][] = 	Carbon::createFromFormat("Y-m-d H:i:s", $category->created_at)->format("d/m/Y - H:i");
                $data[$i][] = 	'<div class="text-right">
                					'.Form::open(['method' => 'DELETE', 'route' => ['categories-blog.destroy', $category->id], 'id'=>'delete-'.$category->id]).'
		            				<div class="btn-group btn-group-sm">
		                    			<a href="'.route('categories-blog.edit', array($category->id)).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
		                    			<a href="#" type="submit" class="btn btn-danger delete" data-entry="'.$category->id.'" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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

            return response()->json($results);
        }
    }

    public function index()
    {
        $ct_categories = BlogCategory::count();
        return view('admin.blog-categories.index', compact('ct_categories'));
    }

    public function getorder()
    {
        $categories = BlogCategory::orderBy('position', 'ASC')->get();
        return view('admin.blog-categories.order', compact('categories'));
    }

    public function set_order_categories_blog(Request $request)
    {
        if ($request->ajax()) {
            if (! $category = BlogCategory::find($request->category_id)) {
                return 'error';
            }
            $category->update(['position' => $request->position]);
        }
    }

    public function edit(BlogCategory $category)
    {
        $languages = Language::all();

        // For Form::model
        $category_arr = [];
        $category_arr['id'] 		= $category->id;
        $category_arr['position'] 	= $category->position;

        // Langues
        foreach ($category->lang as $pl) {
            $category_arr['name_'.$pl->language->short] 			= $pl->name;
            $category_arr['title_'.$pl->language->short] 			= $pl->title;
            $category_arr['keywords_'.$pl->language->short] 		= $pl->keywords;
            $category_arr['description_'.$pl->language->short] 		= $pl->description;
        }

        return view('admin.blog-categories.edit', compact('category', 'languages', 'category_arr'));
    }

    public function update(BlogCategoryRequest $request, BlogCategory $category)
    {
        $category->update($request->all());

        // Langues
        foreach (Language::all() as $l) {
            $input_lang = [
                'blog_category_id'	=> $category->id,
                'language_id' 		=> $l->id,
                'name' 				=> $request->input('name_'.$l->short),
                'title' 			=> $request->input('title_'.$l->short),
                'keywords' 			=> $request->input('keywords_'.$l->short),
                'description' 		=> $request->input('description_'.$l->short),
            ];
            $category_lang = BlogCategoryLang::where('blog_category_id', $input_lang['blog_category_id'])->where('language_id', $input_lang['language_id'])->first();
            if (!$category_lang) {
                BlogCategoryLang::create($input_lang);
            } else {
                $category_lang->update($input_lang);
            }
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'Catégorie <b>'.$category->getAttr('name').'</b> mise à jour']);
        return redirect()->route('categories-blog.index');
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.blog-categories.create', compact('languages'));
    }

    public function store(BlogCategoryRequest $request)
    {
        $category = BlogCategory::create($request->all());

        // Langues
        foreach (Language::all() as $language) {
            $input_lang = [
                'blog_category_id'		=> $category->id,
                'language_id' 			=> $language->id,
                'name' 					=> $request->input('name_'.$language->short),
                'title' 				=> $request->input('title_'.$language->short),
                'keywords' 				=> $request->input('keywords_'.$language->short),
                'description' 			=> $request->input('description_'.$language->short),
            ];
            BlogCategoryLang::create($input_lang);
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'La catégorie <b>'.ucfirst($category->getAttr('name')).'</b> est créée avec succès']);
        return redirect()->route('categories-blog.index');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();

        session()->flash('notification', ['type'=>'error', 'text'=>'Catégorie supprimée']);
        return redirect()->route('categories-blog.index');
    }
}
