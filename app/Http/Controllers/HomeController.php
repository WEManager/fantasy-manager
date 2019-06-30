<?php

namespace App\Http\Controllers;

use App\Club;
use App\TournamentGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $ongoing = TournamentGame::where('status', '1')->get();

        if (!Cache::has('available-clubs')) {
            $clubs = Club::doesntHave('manager')->has('tournament')->inRandomOrder()->take(100)->get();
            Cache::put('available-clubs', $clubs, 5);
        } else {
            $clubs = Cache::get('available-clubs');
        }

        return view('home')->with(['clubs' => $clubs, 'ongoing' => $ongoing]);
    }
}
