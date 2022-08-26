<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
        });

        Schema::table('people', function (Blueprint $table) {
            $table->enum('preferred_foot', ['right', 'left'])->after('id');
            $table->integer('image_url')->after('id');
            $table->integer('skill_moves')->after('id');
            $table->integer('weak_foot')->after('id');
            $table->string('fifa_player_id')->after('id');
            $table->string('know_as')->after('id');
            $table->string('full_name')->after('id');
        });

        Schema::rename('people', 'player');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('player', function (Blueprint $table) {
            // REMOW NEWS
            $table->dropColumn([
                'full_name',
                'know_as',
                'fifa_player_id',
                'weak_foot',
                'skill_moves',
                'image_url'
            ]);
        });

        Schema::table('player', function (Blueprint $table) {
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

        Schema::rename('player', 'people');
    }
};
