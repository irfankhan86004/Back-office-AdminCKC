<?php

namespace App\Http\Controllers;

use App\Mail\BaseEmail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
	public function index()
	{
		return view('welcome');
	}
	
    public function email()
    {
	    Mail::to([
		    'pierre.ckcnet@gmail.com',
		    'pierre.louisot@ckcnet.com'
	    ])->queue(new BaseEmail(['name' => 'Pierre'], 'Nouveau message ;)', 'emails.example'));
    }
}
