<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class MakeTournament extends Command
{
    protected $signature = 'make:tournament {name} {teams=16} {locale=sv} {type=league} {groups=1} {team=senior} {champions=0} {promoted=0} {qualify_up=0} {qualify_down=0} {relegated=0} {generate_teams=true}';

    protected $description = 'Create a new tournament';

    public function handle(): void
    {
        $name = $this->argument('name');
        $type = $this->argument('type');
        $team = $this->argument('team');
        $teams = $this->argument('teams');
        $groups = $this->argument('groups');
        $locale = $this->argument('locale');

        $champions = $this->argument('champions');
        $promoted = $this->argument('promoted');
        $qualify_up = $this->argument('qualify_up');
        $qualify_down = $this->argument('qualify_down');
        $relegated = $this->argument('relegated');

        \App\Generators\Tournament::create([
            'name' => $name,
            'type' => $type,
            'teams' => $teams,
            'team' => $team,
            'groups' => $groups,
            'champions' => $champions,
            'promoted' => $promoted,
            'qualify_up' => $qualify_up,
            'qualify_down' => $qualify_down,
            'relegated' => $relegated,
            'generate_teams' => $this->argument('generate_teams'),
        ]);
    }
}
