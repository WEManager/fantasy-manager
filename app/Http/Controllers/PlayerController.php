<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Player;
use Inertia\Inertia;
use Inertia\Response;

final class PlayerController extends Controller
{
    public function index(): Response
    {
        $players = Player::with(['nation', 'club', 'contract'])->get();

        return Inertia::render('players/list/page', ['players' => $players]);
    }

    public function show(Player $player): Response
    {
        $player->load([
            'nation',
            'club',
            'contract',
        ]);

        return Inertia::render('players/show/page', ['person' => $player]);
    }
}
