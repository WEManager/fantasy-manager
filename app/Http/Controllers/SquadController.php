<?php

namespace App\Http\Controllers;

use App\Club;
use App\Person;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function show(Club $club, $squad)
    {
        $type = [];
        if (strtolower($squad) == 'u19') {
            $type[] = 'youth';
        } elseif (strtolower($squad) == 'u21') {
            $type[] = 'reserve';
        } else {
            $type[] = 'key';
            $type[] = 'regular';
        }

        $players = $club->players($type)
            ->get([
                'person_id',
                'firstname',
                'lastname',
                'age',
                'nationality',
                'form',
            ]);

        return view('squads.show')->with(['club' => $club, 'players' => $players]);
    }
}
