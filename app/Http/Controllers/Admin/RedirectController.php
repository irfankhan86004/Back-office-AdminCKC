<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RedirectExport;
use App\Models\BlogPost;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RedirectRequest;
use App\Models\RedirectU;
use Form;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class RedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:15');
    }
    
    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $redirects = RedirectU::select('redirects.*')
	            ->leftJoin('pages', 'pages.id', '=', 'redirects.page_id')
	            ->leftJoin('blog_posts', 'blog_posts.id', '=', 'redirects.blog_post_id');
            
            $order_array = [
                0 => 'redirects.id',
                1 => 'redirects.active',
                2 => 'redirects.type',
                3 => 'redirects.origin_url',
            ];
            
            if ($search != '') {
                $redirects = $redirects->where(function ($query) use ($search) {
                    $query->where('redirects.id', 'LIKE', '%'.$search.'%')
                        ->orWhere('redirects.type', 'LIKE', '%'.$search.'%')
                        ->orWhere('redirects.origin_url', 'LIKE', '%'.$search.'%');
                });
            }
            
            // Order
            foreach ($request->order as $order) {
                $redirects = $redirects->orderBy($order_array[$order['column']], $order['dir']);
            }
            
            $redirects_total = $redirects->distinct('redirects.id')->count('redirects.id');
            $redirects = $redirects->skip($request->start)->take($request->length)->get();
            
            $recordsTotal = count($redirects);
            $recordsFiltered = $redirects_total;
            $data = [];
            $i = 0;
            foreach ($redirects as $redirect) {
                $data[$i][] = 	'<div class="text-center">'.$redirect->id.'</div>';
                $data[$i][] = 	'<div class="text-center">'.($redirect->active ? '<i class="fa fa-check text-success fa-2x"></i>' : '<i class="fa fa-times text-danger fa-2x"></i>').'</div>';
                $data[$i][] = 	RedirectU::getSelectTypes()[$redirect->type];
                $data[$i][] = 	$redirect->origin_url;
                $data[$i][] = 	$redirect->showRedirection();
                $data[$i][] = 	'<div class="text-right">
                                '.Form::open(['method' => 'DELETE', 'route' => ['redirects.destroy', $redirect->id], 'id'=>'delete-'.$redirect->id]).'
	                            <div class="btn-group btn-group-sm">
	                                <a href="'.route('redirects.edit', [$redirect->id]).'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
	                                <a href="#" type="submit" class="btn btn-danger delete" data-entry="'.$redirect->id.'" data-toggle="tooltip" data-original-title="Delete"><i class="gi gi-remove_2"></i></a>
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
	    $redirectionsCount = RedirectU::count();
        return view('admin.redirects.index', compact('redirectionsCount'));
    }
    
	public function create()
	{
		return view('admin.redirects.create');
	}
    
    public function edit(RedirectU $redirect)
    {
        return view('admin.redirects.edit', compact('redirect'));
    }
	
	public function store(RedirectRequest $request)
	{
		$redirect = RedirectU::create($request->all());
		$this->input_checkbox($request, $redirect, 'active');
		
		session()->flash('notification', ['type' => 'success', 'text' => 'La redirection est ajoutée']);
		return redirect()->route('redirects.index');
	}
    
    public function update(RedirectRequest $request, RedirectU $redirect)
    {
        $redirect->update($request->all());
        $this->input_checkbox($request, $redirect, 'active');
        
        session()->flash('notification', ['type' => 'success', 'text' => 'La redirection est mise à jour']);
        return redirect()->route('redirects.index');
    }
    
    public function destroy(RedirectU $redirect)
    {
        $redirect->delete();
        
        session()->flash('notification', ['type' => 'error', 'text' => 'La redirection est supprimée']);
        return redirect()->back();
    }
    
    public function export()
    {
	    $filename = 'redirections-'.Carbon::now()->format('d_m_Y-H_i').'.csv';
	    return Excel::download(new RedirectExport, $filename);
    }
    
    private function input_checkbox(Request $request, RedirectU $redirect, $field)
    {
        if (! isset($request->$field)) {
            $redirect->$field = 0;
            $redirect->save();
        }
    }
}
