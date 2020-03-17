<?php

namespace App\Http\Controllers;

use App\Federation;
use Illuminate\Http\Request;

class FederationController extends Controller
{
    public function create()
    {
        return view('federations.create');
    }

    public function show(Federation $federation)
    {
        return $federation;
    }
}
