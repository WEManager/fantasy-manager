<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });

        // Insert a season by default
        DB::table('seasons')->insert([
            'start_time' => date('Y-m-d'),
            'end_time' => date('Y-m-d 23:59:59', strtotime('+55 days')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Schema::create('tournament_season', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id');
            $table->integer('season_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('tournament_season');
    }
}
