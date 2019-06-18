<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('hometeam_id');
            $table->integer('awayteam_id');
            $table->string('hometeam_squad')->default('senior');
            $table->string('awayteam_squad')->default('senior');
            $table->integer('hometeam_score')->nullable();
            $table->integer('awayteam_score')->nullable();
            $table->dateTime('start_time');
            $table->enum('type', [
                1, // Only 90 min
                2, // 90 min + penalties
                3, // 90 min + overtime + penalties
            ]);
            $table->enum('status', [
                0, // Not started
                1, // Active
                2, // Ended
                3, // Waiting for second half
                4, // Waiting for extra time
                5, // Waiting for penalties
                6, // Game is cancelled
                7, // Game is postponed
                8, // Not decided yet - maybe not gonna be played
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_games');
    }
}
