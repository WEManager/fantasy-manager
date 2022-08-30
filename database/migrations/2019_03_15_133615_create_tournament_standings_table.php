<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_standings', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('club_id')->constrained();
            $table->foreignId('group_id')->constrained('tournament_groups');
            $table->integer('won')->default(0);
            $table->integer('tie')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('scored')->default(0);
            $table->integer('conceded')->default(0);
            $table->integer('points')->default(0);
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
        Schema::dropIfExists('tournament_standings');
    }
}
