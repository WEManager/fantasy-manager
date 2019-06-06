<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveGameEvent;

class GameEventController extends Controller
{
    public function store(SaveGameEvent $request)
    {
        dd($request);
    }
}
