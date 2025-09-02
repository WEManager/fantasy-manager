<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreatePlayersTable extends Migration
{
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id('id');
            $table->string('full_name');
            $table->string('know_as');
            $table->string('fifa_player_id');
            $table->integer('weak_foot');
            $table->integer('skill_moves');
            $table->enum('preferred_foot', ['right', 'left']);
            $table->integer('length');
            $table->integer('weight');
            $table->unsignedBigInteger('nation_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('preferred_position');
            $table->json('positions');
            $table->json('specialities');
            $table->json('play_styles');
            $table->json('stats');
            $table->timestamps();

            // Foreign key para nations
            $table->foreign('nation_id')->references('id')->on('nations');

            // Índices para melhor performance
            $table->index('preferred_position');
            $table->index('fifa_player_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('players');
    }
}
