<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the game with clubs and leagues';

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
        $italianLeague = \App\Generators\Tournament::create([
            'name' => 'Campionato italiano', 'teams' => 20, 'locale' => 'it',
            'champions' => 1, 'relegated' => 3,
        ]);
        $italianLeague2 = \App\Generators\Tournament::create([
            'name' => 'Seconda lega', 'teams' => 20, 'locale' => 'it',
            'promoted' => 3, 'qualify_down' => 3, 'relegated' => 3,
        ]);
        $italianLeague3 = \App\Generators\Tournament::create([
            'name' => 'Terza lega', 'teams' => 60, 'locale' => 'it', 'type' => 'groups', 'groups' => 3,
            'promoted' => 1, 'qualify_up' => 1, 'qualify_down' => 3, 'relegated' => 3,
        ]);
        $italianLeague4 = \App\Generators\Tournament::create([
            'name' => 'Quarta lega', 'teams' => 162, 'locale' => 'it', 'type' => 'groups', 'groups' => 9,
            'promoted' => 1, 'qualify_up' => 1,
        ]);

        $germanLeague = \App\Generators\Tournament::create([
            'name' => 'Deutsche Liga', 'teams' => 18, 'locale' => 'de',
            'champions' => 1, 'qualify_down' => 1, 'relegated' => 2,
        ]);
        $germanLeague2 = \App\Generators\Tournament::create([
            'name' => 'Zweite Liga', 'teams' => 18, 'locale' => 'de',
            'promoted' => 2, 'qualify_up' => 1, 'qualify_down' => 1, 'relegated' => 2,
        ]);
        $germanLeague3 = \App\Generators\Tournament::create([
            'name' => 'Dritte Liga', 'teams' => 20, 'locale' => 'de',
            'promoted' => 2, 'qualify_up' => 1, 'relegated' => 4,
        ]);
        $germanLeague4 = \App\Generators\Tournament::create([
            'name' => 'Vierte Liga', 'teams' => 72, 'locale' => 'de',
            'type' => 'groups', 'groups' => 4,
            'promoted' => 1,
        ]);

        \App\Generators\Tournament::create([
            'name' => 'Qualifikation für die Deutsche Liga', 'teams' => 2, 'locale' => 'de',
            'promoted' => 1, 'relegated' => 1, 'promote_to' => $germanLeague, 'relegate_to' => $germanLeague2, 'generate_teams' => false,
        ]);
        \App\Generators\Tournament::create([
            'name' => 'Qualifikation für die Zweite Ligaa', 'teams' => 2, 'locale' => 'de',
            'promoted' => 1, 'relegated' => 1, 'promote_to' => $germanLeague2, 'relegate_to' => $germanLeague3, 'generate_teams' => false,
        ]);

        $britishLeague = \App\Generators\Tournament::create([
            'name' => 'British League', 'teams' => 20, 'locale' => 'gb',
            'champions' => 1, 'relegated' => 3,
        ]);
        $secondLeague = \App\Generators\Tournament::create([
            'name' => 'Second League', 'teams' => 24, 'locale' => 'gb',
            'promoted' => 3, 'relegated' => 3,
        ]);
        $thirdLeague = \App\Generators\Tournament::create([
            'name' => 'Third League', 'teams' => 24, 'locale' => 'gb',
            'promoted' => 3, 'relegated' => 4,
        ]);
        $forthLeague = \App\Generators\Tournament::create([
            'name' => 'Fourth League', 'teams' => 24, 'locale' => 'gb',
            'promoted' => 4, 'relegated' => 4,
        ]);
        $fifthLeague = \App\Generators\Tournament::create([
            'name' => 'Fifth League', 'teams' => 24, 'locale' => 'gb',
            'promoted' => 4, 'relegated' => 4,
        ]);
        $sixthLeague = \App\Generators\Tournament::create([
            'name' => 'Sixth League', 'teams' => 48, 'locale' => 'gb',
            'type' => 'groups', 'groups' => 2,
            'promoted' => 2,
        ]);

        $laLiga = \App\Generators\Tournament::create([
            'name' => 'Liga Española', 'teams' => 20, 'locale' => 'es',
            'champions' => 1, 'relegated' => 3,
        ]);
        $segundaLiga = \App\Generators\Tournament::create([
            'name' => 'Segunda Liga', 'teams' => 22, 'locale' => 'es',
            'promoted' => 3, 'relegated' => 4,
        ]);
        $terceraLiga = \App\Generators\Tournament::create([
            'name' => 'Tercera Liga', 'teams' => 80, 'locale' => 'es',
            'type' => 'groups', 'groups' => 4,
            'promoted' => 1, 'relegated' => 4,
        ]);
        $cuartaLiga = \App\Generators\Tournament::create([
            'name' => 'Cuarta Liga', 'teams' => 160, 'locale' => 'es',
            'type' => 'groups', 'groups' => 8,
            'promoted' => 2,
        ]);

        $svenskaLigan = \App\Generators\Tournament::create([
            'name' => 'Svenska ligan', 'teams' => 16, 'locale' => 'sv',
            'champions' => 1, 'qualify_down' => 1, 'relegated' => 2,
        ]);
        $andraDivisionen = \App\Generators\Tournament::create([
            'name' => 'Andra divisionen', 'teams' => 16, 'locale' => 'sv',
            'promoted' => 2, 'qualify_up' => 1, 'qualify_down' => 2, 'relegated' => 2,
        ]);

        $tredjeDivisionen = \App\Generators\Tournament::create([
            'name' => 'Tredje divisionen', 'teams' => 32, 'locale' => 'sv',
            'type' => 'groups', 'groups' => 2,
            'promoted' => 1, 'qualify_up' => 1, 'qualify_down' => 2, 'relegated' => 2,
        ]);

        $fjardeDivisionen = \App\Generators\Tournament::create([
            'name' => 'Fjärde divisionen', 'teams' => 48, 'locale' => 'sv',
            'type' => 'groups', 'groups' => 4,
            'promoted' => 1, 'qualify_up' => 1
        ]);

        $kvalTillSvenskaLigan = \App\Generators\Tournament::create([
            'name' => 'Kval till Svenska ligan', 'teams' => 2, 'locale' => 'sv',
            'promoted' => 1, 'relegated' => 1, 'promote_to' => $svenskaLigan, 'relegate_to' => $andraDivisionen, 'generate_teams' => false,
        ]);

        $kvalTillAndraDivisionen1 = \App\Generators\Tournament::create([
            'name' => 'Kval till Andra divisionen 1', 'teams' => 2, 'locale' => 'sv',
            'promoted' => 1, 'relegated' => 1, 'promote_to' => $andraDivisionen, 'relegate_to' => $tredjeDivisionen, 'generate_teams' => false,
        ]);
        $kvalTillAndraDivisionen2 = \App\Generators\Tournament::create([
            'name' => 'Kval till Andra divisionen 2', 'teams' => 2, 'locale' => 'sv',
            'promoted' => 1, 'relegated' => 1, 'promote_to' => $andraDivisionen, 'relegate_to' => $tredjeDivisionen, 'generate_teams' => false,
        ]);

        $kvalTillTredjeDivisionen1 = \App\Generators\Tournament::create([
            'name' => 'Kval till Tredje divisionen 1', 'teams' => 4, 'locale' => 'sv',
            'promoted' => 2, 'relegated' => 2, 'promote_to' => $tredjeDivisionen, 'relegate_to' => $fjardeDivisionen, 'generate_teams' => false,
        ]);
        $kvalTillTredjeDivisionen2 = \App\Generators\Tournament::create([
            'name' => 'Kval till Tredje divisionen 2', 'teams' => 4, 'locale' => 'sv',
            'promoted' => 2, 'relegated' => 2, 'promote_to' => $tredjeDivisionen, 'relegate_to' => $fjardeDivisionen, 'generate_teams' => false,
        ]);
    }
}
