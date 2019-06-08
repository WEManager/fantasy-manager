<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('age');
            $table->string('nationality');
            $table->integer('birthday'); //1-91 eftersom en säsong är 91 dagar
            $table->integer('form');

            // Goalkeeping
            $table->integer('aerial_reach');
            $table->integer('command_of_area');
            $table->integer('communication');
            $table->integer('eccentricity');
            // $table->integer('first_touch'); already in technical
            $table->integer('handling');
            $table->integer('kicking');
            $table->integer('one_on_ones');
            // $table->integer('passing'); already in technical
            $table->integer('reflexes');
            $table->integer('rushing_out');
            $table->integer('tendency_to_punch');
            $table->integer('throwing');


            // Technical
            $table->integer('corners');
            $table->integer('crossing');
            $table->integer('dribbling');
            $table->integer('finishing');
            $table->integer('first_touch');
            $table->integer('freekick_taking');
            $table->integer('heading');
            $table->integer('long_shots');
            $table->integer('long_throws');
            $table->integer('marking');
            $table->integer('passing');
            $table->integer('penalty_taking');
            $table->integer('tackling');
            $table->integer('technique');


            // Mental
            $table->integer('aggression');
            $table->integer('anticipation');
            $table->integer('bravery');
            $table->integer('composure');
            $table->integer('concentration');
            $table->integer('decisions');
            $table->integer('determination');
            $table->integer('flair');
            $table->integer('leadership');
            $table->integer('off_the_ball');
            $table->integer('positioning');
            $table->integer('teamwork');
            $table->integer('vision');
            $table->integer('work_rate');


            // Physical
            $table->integer('acceleration');
            $table->integer('agility');
            $table->integer('balance');
            $table->integer('jumping_reach');
            $table->integer('natural_fitness');
            $table->integer('pace');
            $table->integer('stamina');
            $table->integer('strength');


            // Hidden
            $table->integer('adaptability');
            $table->integer('consistency');
            $table->integer('dirtiness');
            $table->integer('important_matches');
            $table->integer('injury_proneness');
            $table->integer('versatility');

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
        Schema::dropIfExists('people');
    }
}
