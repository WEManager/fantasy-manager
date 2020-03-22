<?php

namespace App\Http\Controllers;

use App\Club;
use App\JobApplication;
use App\ManagerContract;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerContractController extends Controller
{
    public function create($locale, Club $club)
    {
        if (auth()->check() && auth()->user()->club) {
            return redirect()->back();
        }

        if (auth()->check() && auth()->user()->level === 0) {
            session(['apply_for_job_club' => $club]);
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

    public function store($locale)
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

            return redirect(link_route('show_club', ['club' => $club]))->with('message', __('You got the job as manager for :club!', ['club' => $club->name]));
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

    public function quitJob($locale, Club $club)
    {
        // TODO: Accept to leave if club is not satisfied.
        // TODO: Accept to leave by paying your part of contract to the club.
        // TODO: Do not accept you to leave at all.

        /*$acceptOrDenyRand = rand(0,1);


        if ($acceptOrDenyRand > 0) {
            $boardMessage = __('The board is upset about your request to leave the club. :Club has terminated your contract with immediate effect.', ['Club' => $club->name]);
        } else {
            $boardMessage = __('The board does not want you to leave your job as manager of :Club, but to fulfill your contract. The contract is valid until :Date.', ['Club' => $club->name, 'Date' => date('j/n Y', strtotime($contract->until))]);
        }*/

        $contract = ManagerContract::where('club_id', $club->id)->where('user_id', auth()->id())->where('status', 'ongoing')->whereDate('until', '>', date('Y-m-d'))->first();

        $now = Carbon::now();
        $signed = Carbon::parse($contract->from);
        if ($signed->diffInDays($now) < 14) {

            $boardMessage = __('You cannot resign within 14 days of signing your contract.');

            return view('manager-contracts.quit', ['club' => $club, 'boardMessage' => $boardMessage]);
        }

        $contract->status = 'manager_resigned';
        $contract->save();

        $boardMessage = __('The board is upset about your request to leave the club. :Club has accepted your request and terminated your contract with immediate effect.', ['Club' => $club->name]);

        return view('manager-contracts.quit', ['club' => $club, 'boardMessage' => $boardMessage]);
    }
}
