<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('club_id');
            $table->string('team');

            $table->string('position_1')->default('GK');
            $table->integer('player_1')->nullable();
            $table->string('position_2')->nullable();
            $table->integer('player_2')->nullable();
            $table->string('position_3')->nullable();
            $table->integer('player_3')->nullable();
            $table->string('position_4')->nullable();
            $table->integer('player_4')->nullable();
            $table->string('position_5')->nullable();
            $table->integer('player_5')->nullable();
            $table->string('position_6')->nullable();
            $table->integer('player_6')->nullable();
            $table->string('position_7')->nullable();
            $table->integer('player_7')->nullable();
            $table->string('position_8')->nullable();
            $table->integer('player_8')->nullable();
            $table->string('position_9')->nullable();
            $table->integer('player_9')->nullable();
            $table->string('position_10')->nullable();
            $table->integer('player_10')->nullable();
            $table->string('position_11')->nullable();
            $table->integer('player_11')->nullable();

            $table->string('substitute_1')->nullable();
            $table->integer('player_12')->nullable();
            $table->string('substitute_2')->nullable();
            $table->integer('player_13')->nullable();
            $table->string('substitute_3')->nullable();
            $table->integer('player_14')->nullable();
            $table->string('substitute_4')->nullable();
            $table->integer('player_15')->nullable();
            $table->string('substitute_5')->nullable();
            $table->integer('player_16')->nullable();
            $table->string('substitute_6')->nullable();
            $table->integer('player_17')->nullable();
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
        Schema::dropIfExists('lineups');
    }
}
