<?php

namespace App\Http\Controllers;

use App\Club;
use App\Person;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function show($locale, Club $club, $squad)
    {
        $type = getContractType($squad);

        $players = $club->players($type)
            ->get([
                'person_id',
                'firstname',
                'lastname',
                'age',
                'nationality',
                'form',
            ]);

        if ($squad == 'senior') $squad = '';

        return view('squads.show')->with(['club' => $club, 'players' => $players, 'squad' => $squad]);
    }
}
