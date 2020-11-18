<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Setting;
use App\Http\Requests\SettingRequest;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:11');
    }

    public function index()
    {
        $settings = Setting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(SettingRequest $request)
    {
        $settings = Setting::find(1);
        $settings->update($request->all());
        inputCheckbox($request, $settings, 'mailgun_use');

        session()->flash('notification', ['type' => 'success', 'text' => 'La configuration à été mise à jour avec succès']);

        return redirect()->route('settings.index');
    }
}
