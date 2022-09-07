<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller{
  public function index() {
    $clubs = [];

    if (!Cache::has('available-clubs')) {
      $clubs = Club::doesntHave('manager')->inRandomOrder()->take(100)->get();

      Cache::put('available-clubs', $clubs, 5);
    } else {
      $clubs = Cache::get('available-clubs');
    }

    return Inertia::render('Home', compact('clubs'));
  }
}
