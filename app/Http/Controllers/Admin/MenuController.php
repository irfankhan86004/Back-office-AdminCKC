<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuLang;
use App\Models\Page;
use App\Models\BlogPost;
use Image;
use File;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:3');
    }

    public function index()
    {
        $menu = Menu::find(1);
        return view('admin.menu.index', compact('menu'));
    }

    public function getorder()
    {
        $menus = Menu::where('parent_id', '!=', 0)->orderBy('position', 'ASC')->get();
        return view('admin.menu.order', compact('menus'));
    }

    public function setordermenus(Request $request)
    {
        if ($request->ajax()) {
            if (! $menu = Menu::find($request->menu_id)) {
                return 'error';
            }
            $menu->update(['position' => $request->position]);
        }
    }

    public function edit(Menu $menu)
    {
        $languages = Language::all();

        // For Form::model
        $menu_arr = [];
        $menu_arr['id'] 					= $menu->id;
        $menu_arr['parent_id'] 				= $menu->parent_id;
        $menu_arr['position'] 				= $menu->position;
        $menu_arr['page_id'] 				= $menu->page_id;
        $menu_arr['blog_post_id']           = $menu->blog_post_id;
        $menu_arr['link'] 					= $menu->link;
        $menu_arr['anchor'] 				= $menu->anchor;
        $menu_arr['target'] 				= $menu->target;

        // Langues
        foreach ($menu->lang as $pl) {
            $menu_arr['name_'.$pl->language->short] 			= $pl->name;
            $menu_arr['title_'.$pl->language->short] 			= $pl->title;
            $menu_arr['keywords_'.$pl->language->short] 		= $pl->keywords;
            $menu_arr['description_'.$pl->language->short] 		= $pl->description;
        }

        // Uploads partials
        $model = 'menu';
        $model_id = $menu->id;

        $menu0 = Menu::find(1);
        $select = $this->create_select($menu0);
        unset($select[$menu->id]);

        $pages = $this->select_pages();
        $posts = $this->select_posts();

        return view('admin.menu.edit', compact('menu', 'model', 'model_id', 'languages', 'menu_arr', 'select', 'pages', 'posts'));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        // Check si on a au moins un parent_id = 1
        $check_valid = $this->check_bug_parent($request, $menu);
        if (! $check_valid) {
            session()->flash('notification', ['type'=>'danger', 'text'=>'<b>Attention</b> : le parent \"Menu (par défaut)\" doit avoir au minimum un enfant']);
            return redirect()->back()->withInput($request->all());
        }

        $menu->update($request->all());

        // Langues
        foreach (Language::all() as $l) {
            $input_lang = [
                'menu_id'		=> $menu->id,
                'language_id' 	=> $l->id,
                'name' 			=> $request->input('name_'.$l->short),
                'title' 		=> $request->input('title_'.$l->short),
                'keywords' 		=> $request->input('keywords_'.$l->short),
                'description' 	=> $request->input('description_'.$l->short),
            ];
            $menu_lang = MenuLang::where('menu_id', $menu->id)->where('language_id', $l->id)->first();
            if (!$menu_lang) {
                MenuLang::create($input_lang);
            } else {
                $menu_lang->update($input_lang);
            }
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'Menu <b>'.$menu->getAttr('name').'</b> mis à jour']);
        return redirect()->route('menu.index');
    }

    public function create()
    {
        $languages = Language::all();
        $menu0 = Menu::find(1);
        $select = $this->create_select($menu0);
        $pages = $this->select_pages();
        $posts = $this->select_posts();
        return view('admin.menu.create', compact('languages', 'select', 'pages', 'posts'));
    }

    public function store(MenuRequest $request)
    {
        $menu = Menu::create($request->all());

        // Langues
        foreach (Language::all() as $language) {
            $input_lang = [
                'menu_id'		=> $menu->id,
                'language_id' 	=> $language->id,
                'name' 			=> $request->input('name_'.$language->short),
                'title' 		=> $request->input('title_'.$language->short),
                'keywords' 		=> $request->input('keywords_'.$language->short),
                'description' 	=> $request->input('description_'.$language->short),
            ];
            MenuLang::create($input_lang);
        }

        session()->flash('notification', ['type'=>'success', 'text'=>'Le menu <b>'.ucfirst($menu->getAttr('name')).'</b> est créé avec succès']);
        return redirect()->route('menu.index');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        $this->check_parent_empty();
        session()->flash('notification', ['type'=>'error', 'text'=>'Menu supprimé']);
        return redirect()->route('menu.index');
    }

    private function create_select(Menu $menu, $prefix='-&nbsp;', $enfants=false)
    {
        static $select = [];
        $select[1] = "Menu (par défaut)";
        foreach ($menu->children as $m) {
            if ($enfants) {
                $prefix.='-&nbsp;-&nbsp;';
            }
            $select[$m->id] = $prefix.'&#xf105; '.$m->getAttr('name');
            if (count($m->children)>=1) {
                $this->create_select($m, $prefix, true);
            }
            $enfants = false;
        }
        return $select;
    }

    private function select_pages()
    {
        $pages = [];
        $pages[0] = 'Aucune page';
        foreach (Page::published()->oldest()->get() as $p) {
            $pages[$p->id] = 'Page #'.$p->id.' - '.$p->getAttr('name').' ('.$p->getAttr('url').')';
        }
        return $pages;
    }

    private function select_posts()
    {
        $posts = [];
        $posts[0] = 'Aucun article';
        foreach (BlogPost::published()->oldest()->get() as $p) {
            $posts[$p->id] = 'Article #'.$p->id.' - '.$p->getAttr('name');
        }
        return $posts;
    }

    private function check_bug_parent(Request $request, Menu $menu)
    {
        $nb = 0;
        if ($request->parent_id!=1) {
            foreach (Menu::where('parent_id', '!=', 0)->where('id', '!=', $menu->id)->get() as $m) {
                if ($m->parent_id==1) {
                    $nb++;
                }
            }
            if ($nb>=1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    private function check_parent_empty()
    {
        foreach (Menu::where('parent_id', '!=', 0)->get() as $m) {
            $parent = $m->parent_id;
            if (! $m_p = Menu::find($parent)) {
                $m->parent_id = 1;
                $m->save();
            }
        }
    }
}
