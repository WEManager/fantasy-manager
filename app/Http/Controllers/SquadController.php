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
        $club->loadMissing('manager');

        /** @var \Illuminate\Support\Collection<int,\App\Models\Player> $players */
        $players = $club->players()->get();

        return Inertia::render('clubs/squad/page', [
            'club' => $club,
            'players' => $players,
        ]);
    }
}
