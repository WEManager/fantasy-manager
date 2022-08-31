<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClubController extends Controller {
    public function index() {
        $response = Club::paginate(40);

        return Inertia::render('Club/Index', compact('response'));
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
