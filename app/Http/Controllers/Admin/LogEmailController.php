<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LogEmailRequest;

use App\Models\LogEmail;
use Form;

class LogEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:8');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $emails = LogEmail::select('log_emails.*');

            $order_array = [
                0   => 'log_emails.id',
                1   => 'log_emails.type',
                2   => 'log_emails.from',
                3   => 'log_emails.to',
                4   => 'log_emails.subject',
                5   => 'log_emails.mailgun_status',
                6   => 'log_emails.created_at',
            ];

            if ($search != '') {
                $emails = $emails->where(function ($query) use ($search) {
                    $query->where('log_emails.id', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_emails.type', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_emails.from', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_emails.to', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_emails.subject', 'LIKE', '%'.$search.'%')
                        ->orwhere('log_emails.mailgun_status', 'LIKE', '%'.$search.'%')
                        ->orWhere('log_emails.created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $emails = $emails->orderBy($order_array[$order['column']], $order['dir']);
            }

            $emails_total = $emails->distinct('log_emails.id')->count('log_emails.id');
            $emails = $emails->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($emails);
            $recordsFiltered = $emails_total;
            $data = [];
            $i = 0;

            foreach ($emails as $email) {
                $data[$i][] =   '<div class="text-center">' . $email->id . '</div>';

                $data[$i][] =   $email->type;

                $data[$i][] =   $email->from;

                $data[$i][] =   $email->to;

                $data[$i][] =   $email->subject;
                
                $data[$i][] =   '<div class="text-center"><span class="label label-info">'.$email->mailgun_status.'</span></div>';

                $data[$i][] =   $email->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['emails.destroy', $email->id], 'id' => 'delete-'.$email->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('emails.edit', [$email->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $email->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
        $emails = LogEmail::count();
        return view('admin.log-emails.index', compact('emails'));
    }

    public function edit(LogEmail $email)
    {
        return view('admin.log-emails.edit', compact('email'));
    }

    public function update(LogEmailRequest $request, LogEmail $email)
    {
        $values = $request->all();
        $email->update($values);

        session()->flash('notification', ['type' => 'success', 'text' => 'L\'email a été mis à jour avec succès']);
        return redirect()->route('emails.index');
    }

    public function destroy(LogEmail $email)
    {
        $email->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Email supprimé']);
        return redirect()->route('emails.index');
    }

    public function show($id)
    {
        $email = LogEmail::find($id);
        return $email->message;
    }
}
