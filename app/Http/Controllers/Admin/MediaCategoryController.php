<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MediaCategoryRequest;

use App\Models\MediaCategory;
use Form;

class MediaCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:5');
    }

    public function index()
    {
        $categories = MediaCategory::adminArbo();
        return view('admin.medias-categories.index', compact('categories'));
    }

    public function edit(MediaCategory $category)
    {
        $select = MediaCategory::create_select();

        $other_categories = null;
        if ($category->parent) {
            if ($category->parent->children->count() > 1) {
                foreach ($category->parent->children as $c) {
                    $other_categories[] = $c;
                }
            }
        } else {
            foreach (MediaCategory::whereNull('parent_id')->orderBy('position')->get() as $c) {
                $other_categories[] = $c;
            }
        }

        return view('admin.medias-categories.edit', compact('category', 'select', 'other_categories'));
    }

    public function update(MediaCategoryRequest $request, MediaCategory $category)
    {
        $category->update($request->all());
        session()->flash('notification', ['type'=>'success', 'text'=>'The category '.$category->name.' has been successfully updated']);
        return redirect()->route('categories-medias.index');
    }

    public function create()
    {
        $select = MediaCategory::create_select();
        return view('admin.medias-categories.create', compact('select'));
    }

    public function store(MediaCategoryRequest $request)
    {
        $request->merge(['admin_id' => auth()->guard('admin')->user()->id]);
        $category = MediaCategory::create($request->all());
        if ($category->parent) {
            $position = $category->parent->children->count() + 1;
        } else {
            $position = MediaCategory::where('id', '!=', $category->id)->whereNull('parent_id')->count() + 1;
        }
        $category->update(['position' => $position]);
        session()->flash('notification', ['type'=>'success', 'text'=>'The category '.$category->name.' has been successfully created']);
        return redirect()->route('categories-medias.index');
    }

    public function destroy(MediaCategory $category)
    {
        $parent_id = null;
        if ($category->parent) {
            $parent_id = $category->parent->id;
        }
        foreach ($category->children as $c) {
            $c->update(['parent_id' => $parent_id]);
        }
        $category->delete();
        session()->flash('notification', ['type'=>'error', 'text'=>'The category has been removed']);
        return redirect()->route('categories-medias.index');
    }

    public function order(Request $request)
    {
        if ($request->ajax()) {
            $position = 1;
            foreach ($request->categories as $c) {
                $category = MediaCategory::find($c);
                if ($category) {
                    $category->update(['position' => $position]);
                    $position++;
                }
            }
        }
    }
}
