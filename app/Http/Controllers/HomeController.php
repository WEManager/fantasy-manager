<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $clubs = [];

        // if (auth()->check() && !auth()->user()->club) {
            if (!Cache::has('available-clubs')) {
                $clubs = Club::doesntHave('manager')->inRandomOrder()->take(100)->get();
                
                Cache::put('available-clubs', $clubs, 5);
            } else {
                $clubs = Cache::get('available-clubs');
            }
        // }

        // dd($clubs);

        return Inertia::render('Home', compact('clubs'));
        // return view('home')->with(compact('clubs'));
    }
}
