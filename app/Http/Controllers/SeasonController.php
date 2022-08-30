<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller {
    public function index() {
        $response = Season::paginate();

        return $response;

        // return Inertia::render('Season/Index', compact('response'));
    }

    public function show(Season $season) {
        return $season;

        // return Inertia::render('Season/Show', compact('season'));
    }
}
