<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id');
            $table->integer('person_id');
            $table->enum('type', ['youth', 'reserve', 'regular', 'key']);
            $table->integer('wage');
            $table->integer('sign_on')->nullable();
            $table->dateTime('from');
            $table->dateTime('until');
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
        Schema::dropIfExists('player_contracts');
    }
}
