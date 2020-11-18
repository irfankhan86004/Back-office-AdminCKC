<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequestRequest;

use App\Models\ContactRequest;
use Form;

class ContactRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:14');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $contact_requests = ContactRequest::select('contact_requests.*');

            $order_array = [
                0   => 'contact_requests.id',
                1   => 'contact_requests.last_name',
                2   => 'contact_requests.email',
                4   => 'contact_requests.note',
                6   => 'contact_requests.created_at',
            ];

            if ($search != '') {
                $contact_requests = $contact_requests->where(function ($query) use ($search) {
                    $query->where('contact_requests.id', 'LIKE', '%'.$search.'%')
                        ->orWhere('contact_requests.last_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('contact_requests.first_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('contact_requests.email', 'LIKE', '%'.$search.'%')
                        ->orWhere('contact_requests.phone', 'LIKE', '%'.$search.'%')
                        ->orWhere('contact_requests.created_at', 'LIKE', '%'.date_to_mysql($search).'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $contact_requests = $contact_requests->orderBy($order_array[$order['column']], $order['dir']);
            }

            $contact_requests_total = $contact_requests->distinct('contact_requests.id')->count('contact_requests.id');
            $contact_requests = $contact_requests->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($contact_requests);
            $recordsFiltered = $contact_requests_total;
            $data = [];
            $i = 0;

            foreach ($contact_requests as $contact_request) {
                $data[$i][] =   '<div class="text-center">' . $contact_request->id . '</div>';

                $data[$i][] =   $contact_request->fullName();

                $data[$i][] =   $contact_request->email;

                $data[$i][] =   $contact_request->phone;

                $data[$i][] =   $contact_request->note;

                $data[$i][] =   ($contact_request->lu == 1) ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-times fa-2x text-danger"></i>';

                $data[$i][] =   $contact_request->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['contact-requests.destroy', $contact_request->id], 'id' => 'delete-'.$contact_request->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('contact-requests.edit', [$contact_request->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $contact_request->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
        $contact_requests = ContactRequest::count();
        return view('admin.contact-requests.index', compact('contact_requests'));
    }

    public function edit(ContactRequest $contact_request)
    {
        return view('admin.contact-requests.edit', compact('contact_request'));
    }

    public function update(ContactRequestRequest $request, ContactRequest $contact_request)
    {
        $values = $request->all();
        $contact_request->update($values);

        $this->input_checkbox($request, $contact_request, 'lu');

        session()->flash('notification', ['type' => 'success', 'text' => 'La demande de contact a été mise à jour avec succès']);
        return redirect()->route('contact-requests.index');
    }

    public function destroy(ContactRequest $contact_request)
    {
        $contact_request->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Demande de contact supprimé']);
        return redirect()->route('contact-requests.index');
    }

    private function input_checkbox(Request $request, ContactRequest $contact_request, $field)
    {
        if (! isset($request->$field)) {
            $contact_request->$field = 0;
            $contact_request->save();
        }
    }
}
