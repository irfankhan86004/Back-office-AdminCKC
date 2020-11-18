<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

use App\Models\User;
use Form;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:7');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $users = User::select('users.*');

            $order_array = [
                0   => 'users.id',
                1   => 'users.first_name',
                2   => 'users.created_at',
            ];

            if ($search != '') {
                $users = $users->where(function ($query) use ($search) {
                    $query->where('users.id', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.first_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.last_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $users = $users->orderBy($order_array[$order['column']], $order['dir']);
            }

            $users_total = $users->distinct('users.id')->count('users.id');
            $users = $users->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($users);
            $recordsFiltered = $users_total;
            $data = [];
            $i = 0;

            foreach ($users as $user) {
                $data[$i][] =   '<div class="text-center">'.$user->id.'</div>';

                $data[$i][] = $user->fullName();

                $data[$i][] =   $user->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'id' => 'delete-'.$user->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('users.edit', [$user->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $user->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
        $users = User::count();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $values = $request->all();
        $user->update($values);

        session()->flash('notification', ['type' => 'success', 'text' => 'L\'utilisateur a été mis à jour avec succès']);
        return redirect()->route('users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        session()->flash('notification', ['type' => 'success', 'text' => 'L\'utilisateur a été créé avec succès']);
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Utilisateur supprimé']);
        return redirect()->route('users.index');
    }
}
