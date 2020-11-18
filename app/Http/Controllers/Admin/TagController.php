<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;

use App\Models\Page;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Tag;
use Form;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:13');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $tags = Tag::select('tags.*');

            $order_array = [
                0   => 'tags.id',
                1   => 'tags.title',
                2   => 'tags.layout',
                3   => 'tags.created_at',
            ];

            if ($search != '') {
                $tags = $tags->where(function ($query) use ($search) {
                    $query->where('tags.id', 'LIKE', '%'.$search.'%')
                        ->orWhere('tags.title', 'LIKE', '%'.$search.'%')
                        ->orWhere('tags.position', 'LIKE', '%'.$search.'%')
                        ->orWhere('tags.created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $tags = $tags->orderBy($order_array[$order['column']], $order['dir']);
            }

            $tags_total = $tags->distinct('tags.id')->count('tags.id');
            $tags = $tags->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($tags);
            $recordsFiltered = $tags_total;
            $data = [];
            $i = 0;

            foreach ($tags as $tag) {
                $data[$i][] =   '<div class="text-center">'.$tag->id.'</div>';

                $data[$i][] =   $tag->title;

                $data[$i][] =   ($tag->layout == 1) ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-times fa-2x text-danger"></i>';

                $data[$i][] =   $tag->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['tags.destroy', $tag->id], 'id' => 'delete-'.$tag->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('tags.edit', [$tag->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $tag->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
                                    </div>
                                    '.Form::close().'
                                </div>';
                $i++;
            }

            $results = [
                'draw'              => $request->draw,
                'recordsTotal'      => $recordsTotal,
                'recordsFiltered'   => $recordsFiltered,
                'data'              => $data
            ];

            return response()->json($results);
        }
    }

    public function index()
    {
        $tags = Tag::count();
        return view('admin.tags.index', compact('tags'));
    }

    public function edit(Tag $tag)
    {
        $pages = Page::create_select();
        $blogPosts = BlogPost::create_select();
        $blogCategories = BlogCategory::create_select();
        $positions = Tag::positions();
        return view('admin.tags.edit', compact('tag', 'pages', 'blogPosts', 'blogCategories', 'positions'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->all());
        $this->syncRelationships($tag, $request);

        session()->flash('notification', ['type' => 'success', 'text' => 'Le tag a été mis à jour avec succès']);
        return redirect()->route('tags.index');
    }

    public function create()
    {
        $pages = Page::create_select();
        $blogPosts = BlogPost::create_select();
        $blogCategories = BlogCategory::create_select();
        $positions = Tag::positions();
        return view('admin.tags.create', compact('pages', 'blogPosts', 'blogCategories', 'positions'));
    }

    public function store(TagRequest $request)
    {
        $request->merge(['order' => Tag::count() + 1]);
        $tag = Tag::create($request->all());
        $this->syncRelationships($tag, $request);

        session()->flash('notification', ['type' => 'success', 'text' => 'Le tag a été créé avec succès']);
        return redirect()->route('tags.index');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Tag supprimé']);
        return redirect()->route('tags.index');
    }

    private function input_checkbox(Request $request, Tag $tag, $field)
    {
        if (!isset($request->$field)) {
            $tag->$field = 0;
            $tag->save();
        }
    }

    private function syncRelationships(Tag $tag, $request)
    {
        $relations = ['pages', 'posts', 'blogCategories'];
        foreach ($relations as $relation) {
            $value = $request->{$relation};
            if ($value === null) {
                $value = [];
            }
            $tag->{$relation}()->sync($value);
        }
    }
	
	
	public function tagFetch(Request $request)
    {

		$query = $_GET['query'];
		
        $data = Tag::where('title', 'like', '%'.$query.'%')->limit(200)->get(); 
		
		$tags = [];
		foreach ($data as $value) {
			//$tags[$value->id]['name'] = $value->name;
			//$tags[$value->id]['id'] = $value->id;
			$tags[] = $value->title;
		}
		if (count($tags) <= 0) {
			$tags[] = $query;
		}
		
		return json_encode($tags);
    }
}
