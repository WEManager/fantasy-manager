<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Club;
use Inertia\Inertia;
use Inertia\Response;

final class SquadController extends Controller
{
    public function __invoke(Club $club): Response
    {
        $squad = request()->query('squad', 'senior');
        
        $type = getContractType($squad);
        
        $club->load('manager');

        $players = $club->players($type)->get();

        // Load club with manager relationship
        if ($squad == 'senior') $squad = '';

        return Inertia::render('clubs/squad/page', compact('club', 'players', 'squad'));
    }
}
