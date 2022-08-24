<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'lastname', 'preferred_foot']);

            // Personality
            $table->dropColumn([
                'ambition',
                'controversy',
                'loyalty',
                'pressure',
                'professionalism',
                'sportsmanship',
                'temperament'
            ]);

            // Hidden
            $table->dropColumn([
                'adaptability',
                'consistency',
                'dirtiness',
                'important_matches',
                'injury_proneness',
                'versatility'
            ]);

            $table->string('full_name');
            $table->string('know_as');
            $table->enum('preferred_foot', ['right', 'left']);
            $table->string('fifa_player_id');
            $table->integer('weak_foot');
            $table->integer('skill_moves');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('people', function (Blueprint $table) {
            // REMOW NEWS
            $table->dropColumn([
                'full_name',
                'know_as',
                'preferred_foot',
                'fifa_player_id',
                'weak_foot',
                'skill_moves'
            ]);

            $table->string('firstname');
            $table->string('lastname');

            // Personality
            $table->integer('ambition');
            $table->integer('controversy');
            $table->integer('loyalty');
            $table->integer('pressure');
            $table->integer('professionalism');
            $table->integer('sportsmanship');
            $table->integer('temperament');

            // Hidden
            $table->integer('adaptability');
            $table->integer('consistency');
            $table->integer('dirtiness');
            $table->integer('important_matches');
            $table->integer('injury_proneness');
            $table->integer('versatility');

            /*
             * Only right
             * Right
             * Left
             * Only left
             */
            $table->enum('preferred_foot', ['only_right', 'right', 'left', 'only_left']);
        });
    }
};
