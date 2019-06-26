<?php

namespace App\Http\Controllers;

use App\Club;
use App\JobApplication;
use App\ManagerContract;
use Illuminate\Http\Request;

class ManagerContractController extends Controller
{
    public function create($locale, Club $club)
    {
        if (auth()->check() && auth()->user()->level === 0) {
            return view('manager-contracts.low_level')->with(compact('club'));
        }

        if ($club->manager != null) {
            return redirect()->back()->withErrors([
                __(':Club already has a manager.', ['Club' => $club->name])
            ]);
        }

        if ($jobApplication = JobApplication::where('user_id', auth()->user()->id)->where('club_id', $club->id)->where('status', '!=', 'approved')->first()) {
            return view('manager-contracts.denied')->with(['club' => $club, 'job_application' => $jobApplication]);
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

        // TODO: Implement logic regarding club rejecting job applications not good enough compared to their reputation.
        if (rand(1,5) >= 3) {
            ManagerContract::create([
                'club_id' => $club->id,
                'user_id' => auth()->id(),
                'from' => \request('from'),
                'until' => \request('until'),
                'wage' => \request('wage'),
            ]);

            JobApplication::create([
                'user_id' => auth()->id(),
                'club_id' => $club->id,
                'status' => 'approved',
            ]);

            return redirect(route('show_club', ['club' => $club]));
        } else {
            JobApplication::create([
                'user_id' => auth()->id(),
                'club_id' => $club->id,
                'status' => 'rejected',
            ]);

            return redirect()->back()->withErrors([
                __(':Club did not accept your application.', ['Club' => $club->name])
            ]);
        }
    }
}
