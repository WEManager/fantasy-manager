<?php

namespace App\Console\Commands;

use App\Generators\Tournament;
use App\Models\Season;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ExampleSetup extends Command {
  protected $signature = 'example:setup';

  protected $description = 'Create an example database';

  public function handle() {
    // Artisan::call('upload:nations');
    // Artisan::call('upload:players');

    Season::create([
      'start_time' => now(),
      'end_time' => now()->addDays(30),
    ]);

    Tournament::create([
      'name' => 'Liga WEManager',
      'teams' => 8,
      'champions' => 1,
      'relegated' => 2,
    ]);

    Tournament::create([
      'name' => 'Liga WEManager B',
      'teams' => 8,
      'promoted' => 2,
      'qualify_down' => 2,
      'relegated' => 2,
    ]);

    Tournament::create([
      'name' => 'Copa WEM',
      'teams' => 16,
      'type' => 'groups',
      'groups' => 4,
      'promoted' => 1,
      'qualify_up' => 1,
      'qualify_down' => 1,
      'relegated' => 1,
      'generate_teams' => false
    ]);
  }
}
