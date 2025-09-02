<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateContractsTable extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('club_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->integer('wage');
            $table->integer('sign_on')->nullable();
            $table->dateTime('from');
            $table->dateTime('until');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
