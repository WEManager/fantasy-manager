<?php

namespace App\Http\Controllers;

use App\Club;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clubs = Club::doesntHave('manager')->paginate();

        return view('home')->with(compact('clubs'));
    }
}
