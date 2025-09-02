<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('group_id')->constrained('tournament_groups');
            $table->foreignId('hometeam_id')->constrained('clubs');
            $table->foreignId('awayteam_id')->constrained('clubs');
            $table->integer('hometeam_score')->nullable();
            $table->integer('awayteam_score')->nullable();
            $table->dateTime('start_time');
            $table->enum('type', [
                1, // Regular time only (90 min)
                2, // Regular time + penalties
                3, // Extra time + penalties
            ])->default(1);
            $table->enum('status', [
                0, // Not started
                1, // Active
                2, // Ended
                3, // Waiting for second half
                4, // Waiting for extra time
                5, // Waiting for penalties
                6, // Fixture is cancelled
                7, // Fixture is postponed
                8, // Not decided yet - maybe not gonna be played
            ])->default(0);
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
        Schema::dropIfExists('fixtures');
    }
}
