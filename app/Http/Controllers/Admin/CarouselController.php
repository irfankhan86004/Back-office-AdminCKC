<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarouselRequest;
use App\Models\BlogPost;
use App\Models\Carousel;
use App\Models\CarouselLang;
use App\Models\Page;
use App\Models\Picture;
use App\Models\Language;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Image;

class CarouselController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:10');
    }

    public function index()
    {
        $carousels = Carousel::all();
        $languages = Language::all();
        return view('admin.carousels.index', compact('carousels', 'languages'));
    }

    public function getorder($language_id)
    {
        $carousels = Carousel::orderBy('position', 'ASC')->get();
        return view('admin.carousels.order', compact('carousels'));
    }

    public function setordercarousel(Request $request)
    {
        if ($request->ajax()) {
            if (!$carousel = Carousel::find($request->carousel_id)) {
                return 'error';
            }
            $carousel->update(['position' => $request->position]);
        }
    }

    public function edit(Carousel $carousel)
    {
        $languages = Language::all();

        $carousel_arr = [];
        $carousel_arr['id'] = $carousel->id;
        $carousel_arr['position'] = $carousel->position;
        $carousel_arr['target'] = $carousel->target;
        $carousel_arr['page_id'] = $carousel->page_id;
        $carousel_arr['blog_post_id'] = $carousel->blog_post_id;
        $carousel_arr['published'] = $carousel->published;
        $carousel_arr['background_slide'] = $carousel->background_slide;
        $carousel_arr['background_btn'] = $carousel->background_btn;
        $carousel_arr['link'] = $carousel->link;
        $carousel_arr['language_id'] = $carousel->language_id;

        // Langues
        foreach ($carousel->lang as $cl) {
            $carousel_arr['title_' . $cl->language->short]        = $cl->title;
            $carousel_arr['subtitle_' . $cl->language->short]         = $cl->subtitle;
            $carousel_arr['description_' . $cl->language->short]          = $cl->description;
            $carousel_arr['btn_' . $cl->language->short]     = $cl->btn;
        }

        $pages = $this->select_pages();
        $posts = $this->select_posts();

        // Uploads partials
        $model = 'carousel';
        $model_id = $carousel->id;

        return view('admin.carousels.edit', compact('carousel', 'languages', 'pages', 'posts', 'carousel_arr', 'model', 'model_id'));
    }

    public function update(CarouselRequest $request, Carousel $carousel)
    {
        $carousel->update($request->all());
        $this->input_checkbox($request, $carousel, 'published');

        // Langues
        foreach (Language::all() as $l) {
            $input_lang = [
                'carousel_id' => $carousel->id,
                'language_id' => $l->id,
                'title'       => $request->input('title_'.$l->short),
                'subtitle'    => $request->input('subtitle_'.$l->short),
                'description' => $request->input('description_'.$l->short),
                'btn'         => $request->input('btn_'.$l->short),
            ];

            $carousel_lang = CarouselLang::where('carousel_id', $carousel->id)->where('language_id', $l->id)->first();
            if (!$carousel_lang) {
                $carousel_lang = CarouselLang::create($input_lang);
            } else {
                $carousel_lang->update($input_lang);
            }
        }

        session()->flash('notification', ['type' => 'success', 'text' => 'Le slide #<b>'.$carousel->id.'</b> a été mis à jour avec succès']);

        return redirect()->route('carousel.index');
    }

    public function create()
    {
        $languages = Language::all();
        $pages = $this->select_pages();
        $posts = $this->select_posts();
        return view('admin.carousels.create', compact('languages', 'pages', 'posts'));
    }

    public function store(CarouselRequest $request)
    {
        $carousel = Carousel::create($this->setValues($request));
        $this->input_checkbox($request, $carousel, 'published');

        // Langues
        foreach (Language::all() as $language) {
            $input_lang = [
                'carousel_id'   => $carousel->id,
                'language_id'   => $language->id,
                'title'         => $request->input('title_'.$language->short),
                'subtitle'      => $request->input('subtitle_'.$language->short),
                'description'   => $request->input('description_'.$language->short),
                'btn'           => $request->input('btn_'.$language->short),
            ];
            $carousel_lang = CarouselLang::create($input_lang);
        }

        session()->flash('notification', ['type' => 'success', 'text' => 'Le slide #<b>'.$carousel->id.'</b> a été créé avec succès']);

        return redirect()->route('carousel.index');
    }

    public function destroy(Carousel $carousel)
    {
        // Destroy
        $carousel->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Slide supprimé']);

        return redirect()->route('carousel.index');
    }

    private function setValues(Request $request)
    {
        if ($request->background_slide == '') {
            unset($request['background_slide']);
        }
        if ($request->background_btn == '') {
            unset($request['background_btn']);
        }

        return $request->all();
    }

    private function select_pages()
    {
        $pages = [];
        $pages[null] = 'Aucune page';
        foreach (Page::published()->oldest()->get() as $p) {
            $pages[$p->id] = 'Page #'.$p->id.' - '.$p->getAttr('name').' ('.$p->getAttr('url').')';
        }

        return $pages;
    }

    private function select_posts()
    {
        $posts = [];
        $posts[null] = 'Aucun article';
        $allPosts = BlogPost::oldest()->get();
        foreach ($allPosts as $post) {
            $posts[$post->id] = 'Article #'.$post->id.' - '.$post->getAttr('name');
        }
        return $posts;
    }

    private function input_checkbox(Request $request, Carousel $carousel, $field)
    {
        if (!isset($request->$field)) {
            $carousel->$field = 0;
            $carousel->save();
        }
    }
}
