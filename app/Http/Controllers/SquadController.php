<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class SquadController extends Controller
{
    public function __invoke(Request $request, Club $club): Response
    {
        $squadParam = (string) $request->query('squad', 'senior');
        
        $contractType = getContractType($squadParam);
        
        $club->loadMissing('manager');

        /** @var \Illuminate\Support\Collection<int,\App\Models\Player> $players */
        $players = $club->players($contractType)->get();

        // Load club with manager relationship
        $displaySquad = $squadParam === 'senior' ? '' : $squadParam;

        return Inertia::render('clubs/squad/page', [
            'club'    => $club,
            'players' => $players,
            'squad'   => $displaySquad,
        ]);
    }
}
