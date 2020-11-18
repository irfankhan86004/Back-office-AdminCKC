<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewslettersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewslettersRequest;
use App\Models\Newsletters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Form;
use Maatwebsite\Excel\Facades\Excel;

class NewslettersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:12');
    }
    
    public function ajax_listing(Request $request)
    {
        $search = $request->search['value'];
        $newsletters = Newsletters::select('newsletters.*');
        
        $order_array = [
            0   => 'newsletters.id',
            1   => 'newsletters.email',
            2   => 'newsletters.created_at'
        ];
        
        if ($search != '') {
            $newsletters = $newsletters->where(function ($query) use ($search) {
                $query->where('newsletters.id', 'LIKE', '%'.$search.'%')
                    ->orWhere('newsletters.email', 'LIKE', '%'.$search.'%')
                    ->orWhere('newsletters.created_at', 'LIKE', '%'.$search.'%');
            });
        }
        
        // Order
        foreach ($request->order as $order) {
            $newsletters = $newsletters->orderBy($order_array[$order['column']], $order['dir']);
        }
        
        $newsletters_total = $newsletters->distinct('newsletters.id')->count('newsletters.id');
        $newsletters = $newsletters->skip($request->start)->take($request->length)->get();
        
        $recordsTotal = count($newsletters);
        $recordsFiltered = $newsletters_total;
        $data = [];
        $i = 0;
        
        foreach ($newsletters as $newsletter) {
            $data[$i][] =   '<div class="text-center">'.$newsletter->id.'</div>';
            
            $data[$i][] =   $newsletter->email;
            
            $data[$i][] =  '<b>'.$newsletter->created_at->format('d/m/Y').'</b> - '.$newsletter->created_at->diffForHumans();
            
            $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['newsletters.destroy', $newsletter->id], 'id' => 'delete-'.$newsletter->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $newsletter->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
    
    public function index()
    {
        $newslettersCount = Newsletters::count();
        return view('admin.newsletters.index', compact('newslettersCount'));
    }
    
    public function store(NewslettersRequest $request)
    {
        Newsletters::create($request->all());
        
        session()->flash('notification', [
            'type' => 'success',
            'text' => '<b>'.$request->email.'</b> ajouté'
        ]);
        return redirect()->route('newsletters.index');
    }
    
    public function export()
    {
        $filename = 'newsletters-'.Carbon::now()->format('d_m_Y-H_i').'.csv';
        return Excel::download(new NewslettersExport, $filename);
    }
    
    public function destroy(Newsletters $newsletter)
    {
        $newsletter->delete();
        
        session()->flash('notification', [
            'type' => 'success',
            'text' => '<b>'.$newsletter->email.'</b> supprimé'
        ]);
        return redirect()->route('newsletters.index');
    }
}
