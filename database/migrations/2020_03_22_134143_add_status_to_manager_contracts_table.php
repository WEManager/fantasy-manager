<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToManagerContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_contracts', function (Blueprint $table) {
            $table->enum('status', ['ongoing', 'manager_resigned', 'club_fired'])->default('ongoing')->after('wage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manager_contracts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
