<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;
use Session;
use App\User;
class DashboardController extends Controller
{
    //
	public function index()
    {
       $users = \Auth::user();
	   return view('admin_dashboard')->with('users', $users);
    }
	
}
