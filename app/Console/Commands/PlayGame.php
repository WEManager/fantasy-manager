<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Engines\MatchEngine;
use App\Models\GameEvent;
use App\Models\TournamentGame;
use Illuminate\Console\Command;

final class PlayGame extends Command
{
    protected $signature = 'games:play';

    protected $description = 'Play games';

    public function handle(): void
    {
        // We need to get the entire event objects since we cannot delete them otherwise
        $events = GameEvent::current()->get();

        $ids = $events->pluck('id')->toArray();

        foreach ($events as $event) {
            $event->play();
        }

        // Delete all events that has been played at once
        GameEvent::whereIn('id', $ids)->delete();

        $games = TournamentGame::where('start_time', '<=', now())
            ->where('status', '0')
            ->orWhere('status', '1')
            ->where('start_time', '<=', ago('106 minutes'))
            ->get();

        foreach ($games as $game) {
            new MatchEngine($game);
        }
    }
}
