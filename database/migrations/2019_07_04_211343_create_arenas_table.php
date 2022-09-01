<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArenasTable extends Migration {
    public function up() {
        Schema::create('arenas', function (Blueprint $table) {
            $table->id('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('town');
            $table->integer('stands_no_roof')->default(200);
            $table->integer('stands_roof')->default(0);
            $table->integer('seats_no_roof')->default(0);
            $table->integer('seats_roof')->default(0);
            $table->integer('loges')->default(0);
            $table->foreignId('club_id')->constrained();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('arenas');
    }
}
