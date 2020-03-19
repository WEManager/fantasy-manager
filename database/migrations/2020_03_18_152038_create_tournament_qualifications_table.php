<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_qualifications', function (Blueprint $table) {
            $table->id();
            $table->integer('tournament_id');
            $table->integer('season_id');
            $table->integer('position');
            $table->integer('qualified_for_id'); // tournament id of qualification, or league next season
            $table->boolean('season_ended')->default(true); // false if qualification is needed
            $table->enum('status', ['champions', 'promoted', 'qualify_up', 'ended', 'qualify_down', 'relegated'])->default('ended');
            $table->dateTime('qualification_date');
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
        Schema::dropIfExists('tournament_qualifications');
    }
}
