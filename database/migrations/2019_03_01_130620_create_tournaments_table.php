<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateTournamentsTable extends Migration
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
            $table->integer('participants');
            $table->enum('type', ['league', 'groups', 'playoffs'])->default('league');
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
