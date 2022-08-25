<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClubController extends Controller {
    public function index() {
        $clubs = Club::paginate();

        return Inertia::render('Club/Index', compact('clubs'));
        // return view('clubs.index')->with(compact('clubs'));
    }

    public function show(Club $club) {
        return view('clubs.show')->with(['club' => $club]);
    }

    public function edit(Club $club) {
        return view('clubs.edit')->with(['club' => $club]);
    }

    public function store() {
        return redirect()->back();
    }
}
