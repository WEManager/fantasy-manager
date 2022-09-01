<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration {
  public function up() {
    Schema::create('players', function (Blueprint $table) {
      $table->id('id');

      $table->string('full_name');
      $table->string('know_as');
      $table->string('fifa_player_id');
      $table->integer('weak_foot');
      $table->integer('skill_moves');
      $table->string('image_url');
      $table->enum('preferred_foot', ['right', 'left']);
      $table->integer('age');
      $table->integer('form');
      $table->integer('length');
      $table->integer('weight');

      // Goalkeeping
      $table->integer('aerial_reach');
      $table->integer('command_of_area');
      $table->integer('communication');
      $table->integer('eccentricity');
      $table->integer('handling');
      $table->integer('kicking');
      $table->integer('one_on_ones');
      $table->integer('reflexes');
      $table->integer('rushing_out');
      $table->integer('tendency_to_punch');
      $table->integer('throwing');

      // Technical
      $table->integer('corners');
      $table->integer('crossing');
      $table->integer('dribbling');
      $table->integer('finishing');
      $table->integer('first_touch');
      $table->integer('freekick_taking');
      $table->integer('heading');
      $table->integer('long_shots');
      $table->integer('long_throws');
      $table->integer('marking');
      $table->integer('passing');
      $table->integer('penalty_taking');
      $table->integer('tackling');
      $table->integer('technique');

      // Mental
      $table->integer('aggression');
      $table->integer('anticipation');
      $table->integer('bravery');
      $table->integer('composure');
      $table->integer('concentration');
      $table->integer('decisions');
      $table->integer('determination');
      $table->integer('flair');
      $table->integer('leadership');
      $table->integer('off_the_ball');
      $table->integer('positioning');
      $table->integer('teamwork');
      $table->integer('vision');
      $table->integer('work_rate');

      // Physical
      $table->integer('acceleration');
      $table->integer('agility');
      $table->integer('balance');
      $table->integer('jumping_reach');
      $table->integer('natural_fitness');
      $table->integer('pace');
      $table->integer('stamina');
      $table->integer('strength');

      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('players');
  }
}
