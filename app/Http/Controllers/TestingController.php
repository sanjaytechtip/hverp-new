<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class TestingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
	
	public function testing()
    {
		echo balanceStockUpdate(51047);
    }
}
