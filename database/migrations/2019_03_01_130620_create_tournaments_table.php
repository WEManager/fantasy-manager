<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('slug');
            // $table->integer('recurring_every_of_year')->nullable();
            $table->integer('participants');
            $table->enum('type', ['league', 'groups', 'playoffs'])->default('league');
            // $table->enum('team', ['senior', 'u21', 'u19'])->default('senior');
            $table->integer('groups')->nullable();
            $table->boolean('playoffs')->default(false);
            $table->integer('proceeding_to_playoffs')->nullable();
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
        Schema::dropIfExists('tournaments');
    }
}
