<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineupsTable extends Migration {
  public function up() {
    Schema::create('lineups', function (Blueprint $table) {
      $table->id('id');

      $table->foreignId('club_id')->constrained();

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

      $table->integer('substitute_1')->nullable();
      $table->integer('substitute_2')->nullable();
      $table->integer('substitute_3')->nullable();
      $table->integer('substitute_4')->nullable();
      $table->integer('substitute_5')->nullable();
      $table->integer('substitute_6')->nullable();
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('lineups');
  }
}
