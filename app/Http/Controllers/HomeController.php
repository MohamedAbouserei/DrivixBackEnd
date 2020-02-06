<?php

namespace App\Http\Controllers;

use App\Gasstation;
use App\Order;
use App\Sparesshop;
use App\Workshop;
use Illuminate\Http\Request;
use Auth;
class HomeController extends Controller
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
        $g_number = Gasstation::count();
        $ws_number = Workshop::count();
        $sp_number = Sparesshop::count();
        $o_number = Order::count();
        return view('home' , compact('g_number' ,'ws_number' , 'sp_number' , 'o_number'));
    }
}
