<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Language;
use App\Models\Media;
use App\Models\MediaType;
use App\Models\Picture;
use App\Models\PictureLang;
use Image;
use File;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:1');
    }

    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $admin_roles = $admin->roles->pluck(['id'])->toArray();

        return view('admin.admins.edit', compact('admin', 'roles', 'admin_roles'));
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        $values = $request->all();
        if (trim($request->password) == '') {
            $values = $request->except('password');
        }
        $admin->update($values);
        $admin->roles()->sync($request->roles);

        $admin->setMedia($request);

        session()->flash('notification', ['type' => 'success', 'text'=> 'L\'admin a été mis à jour avec succès']);
        return redirect()->route('admins.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $admin = Admin::create($request->all());
        $admin->setMedia($request);

        $admin->roles()->sync($request->roles);

        session()->flash('notification', ['type' => 'success', 'text' => 'L\'admin a été créé avec succès']);
        return redirect()->route('admins.index');
    }

    public function destroy(Admin $admin)
    {
        $admin->removeMedia();
        $admin->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Admin supprimé']);
        return redirect()->route('admins.index');
    }

    public function remove_avatar(Request $request)
    {
        if ($request->ajax()) {
            $admin = Admin::find($request->admin_id);
            if ($admin) {
                $admin->removeMedia();
                return json_encode(['result' => true]);
            }
        }
    }
}
