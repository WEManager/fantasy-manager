<?php

namespace App\Console\Commands;

use App\Models\Club;
use App\Events\CreateLeagueEvent;
use App\Season;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentQualification;
use App\Models\TournamentSeason;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeTournament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tournament {name} {teams=16} {locale=sv} {type=league} {groups=1} {team=senior} {champions=0} {promoted=0} {qualify_up=0} {qualify_down=0} {relegated=0} {generate_teams=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tournament';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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

        $tournamentId = \App\Generators\Tournament::create([
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
            'locale' => localeBasedOnNationality($locale),
        ]);
    }
}
