<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('contracts', function (Blueprint $table) {
      $table->enum('type', ['key', 'regular', 'youth', 'reserve'])->default('regular')->after('player_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('contracts', function (Blueprint $table) {
      $table->dropColumn('type');
    });
  }
};
