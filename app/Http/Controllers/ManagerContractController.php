<?php

namespace App\Http\Controllers;

use App\Club;
use App\ManagerContract;
use Illuminate\Http\Request;

class ManagerContractController extends Controller
{
    public function create(Club $club)
    {
        if ($club->manager != null) {
            return redirect()->back()->withErrors([
                __(':Club already has a manager.', ['Club' => $club->name])
            ]);
        }

        return view('manager-contracts.create')->with(compact('club'));
    }

    public function store()
    {
        $club = Club::find(\request('club_id'));

        if ($club->manager != null) {
            return redirect()->back()->withErrors([
                __(':Club already has a manager.', ['Club' => $club->name])
            ]);
        }

        if (rand(1,5) >= 3) {
            ManagerContract::create([
                'club_id' => $club->id,
                'user_id' => auth()->id(),
                'from' => \request('from'),
                'until' => \request('until'),
                'wage' => \request('wage'),
            ]);

            return redirect(route('show_club', ['club' => $club]));
        } else {
            return redirect()->back()->withErrors([
                __(':Club did not accept your application.', ['Club' => $club->name])
            ]);
        }
    }
}
