<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $clubs = [];

    if (!Cache::has('available-clubs')) {
      $clubs = Club::doesntHave('manager')->inRandomOrder()->take(100)->get();

      Cache::put('available-clubs', $clubs, 5);
    } else {
      $clubs = Cache::get('available-clubs');
    }


    return inertia('home/page', compact('clubs'));
  }
}
