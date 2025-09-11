<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\Contract;
use App\Models\Lineup;
use Illuminate\Console\Command;

final class GenerateLineups extends Command
{
    protected $signature = 'lineups:generate';

    protected $description = 'Generate lineups for all clubs';

    public function handle(): void
    {
        $clubs = Club::all();

        foreach ($clubs as $club) {
            $this->info("Generating lineup for {$club->name}...");

            // Buscar jogadores ativos do clube
            $players = Contract::where('club_id', $club->id)
                ->where('from', '<=', now())
                ->where('until', '>=', now())
                ->with('player')
                ->get()
                ->pluck('player')
                ->filter();

            if ($players->count() < 11) {
                $this->warn("Club {$club->name} has only {$players->count()} players. Skipping...");

                continue;
            }

            // Verificar se já existe lineup
            $existingLineup = Lineup::where('club_id', $club->id)->first();
            if ($existingLineup) {
                $this->info("Lineup already exists for {$club->name}. Skipping...");

                continue;
            }

            // Criar lineup com os primeiros 11 jogadores
            $lineup = new Lineup();
            $lineup->club_id = $club->id;

            $playerIds = $players->take(11)->pluck('id')->toArray();

            for ($i = 1; $i <= 11; $i++) {
                $lineup->{'player_'.$i} = $playerIds[$i - 1] ?? null;
            }

            $lineup->save();
            $this->info("Lineup created for {$club->name} with ".count($playerIds).' players.');
        }

        $this->info('Lineup generation completed!');
    }
}
