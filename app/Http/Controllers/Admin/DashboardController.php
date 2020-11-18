<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
