<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BlogPostRequest;

use App\Models\Language;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\BlogPostLang;
use App\Models\BlogCategory;
use App\Models\BlogCategoryLang;
use App\Models\PageLang;
use App\Models\PageType;
use Illuminate\Support\Facades\URL;
use Image;
use File;
use Form;
use DB;
use App\Models\Tag;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:2');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $posts = BlogPost::select('blog_posts.*')
                            ->join('blog_posts_lang', 'blog_posts.id', '=', 'blog_posts_lang.blog_post_id')
                            ->leftJoin('blog_posts_categories', 'blog_posts.id', '=', 'blog_posts_categories.blog_post_id')
                            ->leftJoin('blog_categories', 'blog_posts_categories.blog_category_id', '=', 'blog_categories.id')
                            ->leftJoin('blog_categories_lang', 'blog_categories_lang.blog_category_id', '=', 'blog_categories.id')
                            ->leftJoin('admins', 'blog_posts.admin_id', '=', 'admins.id');

            $order_array = [
                0 	=> 'blog_posts.id',
                1 	=> 'blog_posts_lang.name',
                2 	=> 'blog_posts.published',
                4 	=> 'blog_posts.date',
                5 	=> 'admins.last_name',
            ];

            if ($search!='') {
                $posts = $posts->where(function ($query) use ($search) {
                    $query->where('blog_posts.id', 'LIKE', '%'.$search.'%')
                          ->orWhere('blog_posts_lang.name', 'LIKE', '%'.$search.'%')
                          ->orWhere('blog_posts.published', 'LIKE', '%'.$search.'%')
                          ->orWhere('blog_categories_lang.name', 'LIKE', '%'.$search.'%')
                          ->orWhere('blog_posts.date', 'LIKE', '%'.$search.'%')
                          ->orWhere(DB::raw('CONCAT(admins.last_name," ",admins.first_name)'), 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $posts = $posts->orderBy($order_array[$order['column']], $order['dir']);
            }

            $posts_total = $posts->count('blog_posts.id');
            $posts = $posts->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($posts);
            $recordsFiltered = $posts_total;
            $data = [];
            $i = 0;
            foreach ($posts as $post) {
                $data[$i][] = 	'<div class="text-center">'.$post->id.'</div>';
                $data[$i][] = 	'<a href="' . route('articles-blog.edit', $post->id) . '">' . ucfirst($post->getAttr('name')) . '</a>';
                $data[$i][] = 	'<div class="text-center">' . ($post->published ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>') . '</div>';
                $data[$i][] = 	$post->displayAdminCategories();
                $data[$i][] = 	$post->date.' à '.$post->heure;
                $data[$i][] = 	$post->admin ? $post->admin->displayName() : '-';
                $data[$i][] = 	'<div class="text-right">
                					'.Form::open(['method' => 'DELETE', 'route' => ['articles-blog.destroy', $post->id], 'id'=>'delete-'.$post->id]).'
		            				<div class="btn-group btn-group-sm">
		                    			<a href="'.route('articles-blog.edit', array($post->id)).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
		                    			<a href="#" type="submit" class="btn btn-danger delete" data-entry="'.$post->id.'" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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

    public function index()
    {
        $posts = BlogPost::count();
        return view('admin.blog-articles.index', compact('posts'));
    }

    public function edit(BlogPost $post)
    {
        $languages = Language::all();

        // For Form::model
        $post_arr = [];
        $post_arr['id'] 					= $post->id;
        $post_arr['categories'] 			= $post->categories;
        $post_arr['featured'] 				= $post->featured;
        $post_arr['published'] 				= $post->published;
        $post_arr['date'] 					= $post->date;
        $post_arr['heure'] 					= $post->heure;
        $post_arr['date_hide'] 				= $post->date_hide;
        $post_arr['written_by'] 			= $post->written_by;

        // Langues
        foreach ($post->lang as $pl) {
            $post_arr['title_'.$pl->language->short] 		= $pl->title;
            $post_arr['name_'.$pl->language->short] 		= $pl->name;
            $post_arr['url_'.$pl->language->short] 			= $pl->url;
            $post_arr['keywords_'.$pl->language->short] 	= $pl->keywords;
            $post_arr['description_'.$pl->language->short] 	= $pl->description;
            $post_arr['big_title_'.$pl->language->short]         = $pl->big_title;
            $post_arr['text_'.$pl->language->short] 		= $pl->text;
        }

        // Uploads partials
        $model 				 = 'blog_post';
        $model_id 			 = $post->id;

        $categories = $this->select_categories();
		
		
		$blogTag = BlogTag::where('id_blog', $post->id)->get();

        return view('admin.blog-articles.edit', compact('blogTag', 'post', 'languages', 'post_arr', 'categories', 'model', 'model_id'));
    }

    public function update(BlogPostRequest $request, BlogPost $post)
    {
        $post->update($request->all());
		
		$meta_tag = isset($_POST['hidden-meta_tag']) ? $_POST['hidden-meta_tag'] : '';
		if (!empty($meta_tag)) {
			$metaTags = explode(',', $meta_tag);
			
			BlogTag::where('id_blog', $post->id)->delete();
			
			foreach ($metaTags as $metaTag) {
				
				$tagcount = Tag::where('title', trim($metaTag))->count();
				
				if ($tagcount == 0) {
					$tag = new Tag();		
					$tag->title = $metaTag;
					$tag->position = 'head';
					$tag->layout = '0';
					$tag->order = Tag::count() + 1;
					$tag->content = $metaTag;
					$tag->save();
				} else {
					$tag = Tag::where('title', trim($metaTag))->get()->first();	
				}
				
				$blogTag = new BlogTag();		
				$blogTag->id_blog = $post->id;
				$blogTag->id_tag = $tag->id;
				$blogTag->save();
			}
					
        } 
		
        $this->input_checkbox($request, $post, 'published');
        $this->input_checkbox($request, $post, 'featured');
        $this->input_checkbox($request, $post, 'date_hide');

        // Langues
        foreach (Language::all() as $l) {
            $input_lang = [
                'blog_post_id'	=> $post->id,
                'language_id' 	=> $l->id,
                'name' 			=> $request->input('name_'.$l->short),
                'title' 		=> $request->input('title_'.$l->short),
                'keywords' 		=> $request->input('keywords_'.$l->short),
                'description' 	=> $request->input('description_'.$l->short),
                'big_title'     => $request->input('big_title_'.$l->short),
                'text' 			=> $request->input('text_'.$l->short),
            ];

            $blog_post_lang = BlogPostLang::where('blog_post_id', $post->id)->where('language_id', $l->id)->first();
            if (!$blog_post_lang) {
                $blog_post_lang = BlogPostLang::create($input_lang);
            } else {
                $blog_post_lang->update($input_lang);
            }
            $this->setUrl($blog_post_lang, $request->input('url_'.$l->short));
        }

        $this->syncCategories($post, $request->categories);

        session()->flash('notification', ['type'=>'success', 'text'=>'Article <b>'.$post->getAttr('name').'</b> mis à jour']);
        return redirect()->route('articles-blog.index');
    }

    public function create()
    {
        $languages = Language::all();
        $categories = $this->select_categories();
        return view('admin.blog-articles.create', compact('languages', 'categories'));
    }

    public function store(BlogPostRequest $request)
    {
		$request->merge([
            'admin_id' => auth()->guard('admin')->user()->id,
        ]);
        $post = BlogPost::create($request->all());
		
		//--- Logic Section Ends
		$meta_tag = isset($_POST['hidden-meta_tag']) ? $_POST['hidden-meta_tag'] : '';
		
		if (!empty($meta_tag)) {
			$metaTags = explode(',', $meta_tag);
			
			foreach ($metaTags as $metaTag) {
				
				$tagcount = Tag::where('title', trim($metaTag))->count();
				
				if ($tagcount == 0) {
					$tag = new Tag();		
					$tag->title = $metaTag;
					$tag->position = 'head';
					$tag->layout = '0';
					$tag->order = Tag::count() + 1;
					$tag->content = $metaTag;
					$tag->save();
				} else {
					$tag = Tag::where('title', trim($metaTag))->get()->first();	
				}
				
				$blogTag = new BlogTag();		
				$blogTag->id_blog = $post->id;
				$blogTag->id_tag = $tag->id;
				$blogTag->save();
			}
					
        } 
		
        //--- Logic Section Ends
		

        // Langues
        foreach (Language::all() as $language) {
            $input_lang = [
                'blog_post_id'	=> $post->id,
                'language_id' 	=> $language->id,
                'name' 			=> $request->input('name_'.$language->short),
                'title' 		=> $request->input('title_'.$language->short),
                'keywords' 		=> $request->input('keywords_'.$language->short),
                'description' 	=> $request->input('description_'.$language->short),
                'big_title'     => $request->input('big_title_'.$language->short),
                'text' 			=> $request->input('text_'.$language->short),
            ];
            $blog_post_lang = BlogPostLang::create($input_lang);
            $this->setUrl($blog_post_lang, $request->input('url_'.$language->short));
        }

        $this->syncCategories($post, $request->categories);
		
		
		
        if ($request->input('upload_medias') == 1) {
            return redirect()->route('articles-blog.edit', [$post->id]);
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'L\'article <b>'.ucfirst($post->getAttr('name')).'</b> est créé avec succès']);
        return redirect()->route('articles-blog.index');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();

        session()->flash('notification', ['type'=>'error', 'text'=>'Article supprimé']);
        return redirect()->route('articles-blog.index');
    }

    private function input_checkbox(Request $request, BlogPost $post, $field)
    {
        if (! isset($request->$field)) {
            $post->$field = 0;
            $post->save();
        }
    }

    private function syncCategories(BlogPost $post, $categories)
    {
        if ($categories === null) {
            $categories = [];
        }
        $post->categories()->sync($categories);
    }

    private function setUrl(BlogPostLang $post_lang, $url)
    {
        if (null !== $url && trim($url) != '') {
            $blog_post_url = trim($url);
        } else {
            $blog_post_url = $post_lang->article->id.'-'.trim($post_lang->name);
        }
        $post_lang->update(['url' => str_slug($blog_post_url)]);
    }

    private function select_categories()
    {
        $categories = [];
        foreach (BlogCategory::oldest()->get() as $p) {
            $categories[$p->id] = $p->getAttr('name');
        }
        return $categories;
    }
}
