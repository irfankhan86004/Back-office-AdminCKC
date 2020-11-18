<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Input;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $guard = 'admin';

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin_dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $remember = isset($input['rememberme']) ? true : false;

        $auth = auth()->guard('admin');

        if ($auth->attempt(['email' => $input['email'], 'password' => $input['password']], $remember)) {
            return \Redirect::route('admin_dashboard')->withNotification(['type' => 'success', 'text' => 'Bonjour '.Auth::guard('admin')->user()->displayName().' !']);
        }

        return \Redirect::back()->withInput()->withNotification(['type' => 'error', 'text' => 'Identifiants incorrects']);
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return \Redirect::route('admin')->withNotification(['type' => 'warning', 'text' => 'Vous êtes maintenant déconnecté']);
    }
}
