<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Inertia\Inertia;

class ClubController extends Controller {
  public function index() {
    $response = Club::paginate(40);

    return Inertia::render('Club/Index', compact('response'));
  }

  public function show(Club $club) {
    $club
      ->load('players')
      ->append('games');

    return Inertia::render('Club/Show', compact('club'));
  }
}
