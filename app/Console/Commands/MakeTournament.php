<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TournamentType;
use Illuminate\Console\Command;

final class MakeTournament extends Command
{
    protected $signature = 'make:tournament {name} {teams=16} {locale=sv} {type=league} {groups=1} {champions=0} {promoted=0} {qualify_up=0} {qualify_down=0} {relegated=0} {generate_teams=true}';

    protected $description = 'Create a new tournament';

    public function handle(): void
    {
        /** @var string $name */
        $name = $this->argument('name');
        /** @var string $type */
        $type = $this->argument('type');
        /** @var int $teams */
        $teams = $this->argument('teams');
        /** @var int $groups */
        $groups = $this->argument('groups');
        /** @var int $champions */
        $champions = $this->argument('champions');
        /** @var int $promoted */
        $promoted = $this->argument('promoted');
        /** @var int $qualify_up */
        $qualify_up = $this->argument('qualify_up');
        /** @var int $qualify_down */
        $qualify_down = $this->argument('qualify_down');
        /** @var int $relegated */
        $relegated = $this->argument('relegated');
        /** @var bool $generate_teams */
        $generate_teams = $this->argument('generate_teams');

        // Validar se o tipo é válido
        if (! in_array($type, array_column(TournamentType::cases(), 'value'))) {
            $this->error('Tipo inválido. Tipos disponíveis: '.implode(', ', array_column(TournamentType::cases(), 'value')));

            return;
        }

        \App\Generators\Tournament::create([
            'name' => $name,
            'type' => TournamentType::from($type),
            'teams' => $teams,
            'groups' => $groups,
            'champions' => $champions,
            'promoted' => $promoted,
            'qualify_up' => $qualify_up,
            'qualify_down' => $qualify_down,
            'relegated' => $relegated,
            'generate_teams' => $generate_teams,
        ]);
    }
}
