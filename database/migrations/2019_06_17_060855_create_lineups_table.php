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
            $table->integer('player_1');
            $table->string('position_2');
            $table->integer('player_2');
            $table->string('position_3');
            $table->integer('player_3');
            $table->string('position_4');
            $table->integer('player_4');
            $table->string('position_5');
            $table->integer('player_5');
            $table->string('position_6');
            $table->integer('player_6');
            $table->string('position_7');
            $table->integer('player_7');
            $table->string('position_8');
            $table->integer('player_8');
            $table->string('position_9');
            $table->integer('player_9');
            $table->string('position_10');
            $table->integer('player_10');
            $table->string('position_11');
            $table->integer('player_11');

            $table->string('substitute_1');
            $table->integer('player_12');
            $table->string('substitute_2');
            $table->integer('player_13');
            $table->string('substitute_3');
            $table->integer('player_14');
            $table->string('substitute_4');
            $table->integer('player_15');
            $table->string('substitute_5');
            $table->integer('player_16');
            $table->string('substitute_6');
            $table->integer('player_17');
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
