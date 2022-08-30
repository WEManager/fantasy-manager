<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class SquadController extends Controller
{
    public function show(Club $club)
    {
        $squad = 'senior';

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
