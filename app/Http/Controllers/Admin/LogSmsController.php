<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LogSmsRequest;

use App\Models\LogSms;
use Form;

class LogSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:8');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $sms = LogSms::select('log_sms.*');

            $order_array = [
                0   => 'log_sms.id',
                1   => 'log_sms.slug',
                2   => 'log_sms.subject',
                3   => 'log_sms.to',
                4   => 'log_sms.created_at',
            ];

            if ($search != '') {
                $sms = $sms->where(function ($query) use ($search) {
                    $query->where('log_sms.id', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_sms.slug', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_sms.subject', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_sms.to', 'LIKE', '%'.$search.'%')
                        ->orWhere('log_sms.created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $sms = $sms->orderBy($order_array[$order['column']], $order['dir']);
            }

            $sms_total = $sms->distinct('log_sms.id')->count('log_sms.id');
            $sms = $sms->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($sms);
            $recordsFiltered = $sms_total;
            $data = [];
            $i = 0;

            foreach ($sms as $s) {
                $data[$i][] =   '<div class="text-center">' . $s->id . '</div>';

                $data[$i][] =   $s->slug;

                $data[$i][] =   $s->to;

                $data[$i][] =   $s->subject;

                $data[$i][] =   $s->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['sms.destroy', $s->id], 'id' => 'delete-'.$s->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('sms.edit', [$s->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $s->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
        $sms = LogSms::count();
        return view('admin.log-sms.index', compact('sms'));
    }

    public function edit($id)
    {
        $sms = LogSms::find($id);
        return view('admin.log-sms.edit', compact('sms'));
    }

    public function update(LogSmsRequest $request, LogSms $sms)
    {
        $values = $request->all();
        $sms->update($values);

        session()->flash('notification', ['type' => 'success', 'text' => 'Le SMS a été mis à jour avec succès']);
        return redirect()->route('sms.index');
    }

    public function destroy($id)
    {
        $sms = LogSms::find($id);
        $sms->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'SMS supprimé']);
        return redirect()->route('sms.index');
    }
}
