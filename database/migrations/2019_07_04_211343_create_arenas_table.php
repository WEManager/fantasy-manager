<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArenasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arenas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('town');
            $table->integer('stands_no_roof')->default(200);
            $table->integer('stands_roof')->default(0);
            $table->integer('seats_no_roof')->default(0);
            $table->integer('seats_roof')->default(0);
            $table->integer('loges')->default(0);
            $table->timestamps();
        });

        Schema::create('club_arenas', function (Blueprint $table) {
            $table->integer('club_id');
            $table->integer('arena_id');
            $table->string('team');

            $table->unique(['club_id', 'team']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arenas');
        Schema::dropIfExists('club_arenas');
    }
}
