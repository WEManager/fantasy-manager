<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_quiz_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('license_quiz_id');
            $table->string('question');
            $table->json('answers');
            $table->string('correct_answer');
            $table->timestamps();
        });

        DB::table('license_quiz_questions')->insert([
            [
                'license_quiz_id' => 1,
                'question' => 'Do you win the game if you score more goals than your opponent?',
                'answers' => json_encode(['Yes.', 'No.', 'The referee wins and collect all 3 points.']),
                'correct_answer' => 'Yes.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'license_quiz_id' => 1,
                'question' => 'What will happen if a player get two yellow cards in a game?',
                'answers' => json_encode([
                    'The player will be sent off.',
                    'The player can continue the game, but will be suspended in the next game.',
                    'The referee will die.',
                ]),
                'correct_answer' => 'The player will be sent off.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'license_quiz_id' => 1,
                'question' => 'What is offside?',
                'answers' => json_encode([
                    'When a player takes the ball with his hands.',
                    'When the teams switch side of the pitch.',
                    'When an offensive player is behind the last defender when a pass is created.',
                ]),
                'correct_answer' => 'When an offensive player is behind the last defender when a pass is created.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'license_quiz_id' => 1,
                'question' => 'What of following is reasonable to do during a game?',
                'answers' => json_encode([
                    'Developing nuclear weapons.',
                    'Practising making babies.',
                    'Heading the ball to a teammate.',
                ]),
                'correct_answer' => 'Heading the ball to a teammate.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'license_quiz_id' => 1,
                'question' => 'Who is in charge of the teams performance?',
                'answers' => json_encode([
                    'Bobs mom.',
                    'Most certainly Steve Jobs.',
                    'You as a manager.',
                ]),
                'correct_answer' => 'You as a manager.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('license_quiz_questions');
    }
}
