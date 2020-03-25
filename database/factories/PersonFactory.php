<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Person::class, function (Faker $faker) {

    $nationalities = ['SE', 'ES', 'DE', 'IT', 'GB'];
    shuffle($nationalities);

    $preferredFoot = ['only_right', 'right', 'left', 'only_left'];
    shuffle($preferredFoot);

    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'age' => rand(15,35),
        'nationality' => $nationalities[0],
        'birthday' => rand(1,55),
        'form' => rand(1,99),
        'length' => rand(150,210),
        'weight' => rand(60,150),
        'preferred_foot' => $preferredFoot[0],
        'ambition' => rand(1,99),
        'controversy' => rand(1,99),
        'loyalty' => rand(1,99),
        'pressure' => rand(1,99),
        'professionalism' => rand(1,99),
        'sportsmanship' => rand(1,99),
        'temperament' => rand(1,99),
        'aerial_reach' => rand(1,99),
        'command_of_area' => rand(1,99),
        'communication' => rand(1,99),
        'eccentricity' => rand(1,99),
        'handling' => rand(1,99),
        'kicking' => rand(1,99),
        'one_on_ones' => rand(1,99),
        'reflexes' => rand(1,99),
        'rushing_out' => rand(1,99),
        'tendency_to_punch' => rand(1,99),
        'throwing' => rand(1,99),

        'corners' => rand(1,99),
        'crossing' => rand(1,99),
        'dribbling' => rand(1,99),
        'finishing' => rand(1,99),
        'first_touch' => rand(1,99),
        'freekick_taking' => rand(1,99),
        'heading' => rand(1,99),
        'long_shots' => rand(1,99),
        'long_throws' => rand(1,99),
        'marking' => rand(1,99),
        'passing' => rand(1,99),
        'penalty_taking' => rand(1,99),
        'tackling' => rand(1,99),
        'technique' => rand(1,99),

        'aggression' => rand(1,99),
        'anticipation' => rand(1,99),
        'bravery' => rand(1,99),
        'composure' => rand(1,99),
        'concentration' => rand(1,99),
        'decisions' => rand(1,99),
        'determination' => rand(1,99),
        'flair' => rand(1,99),
        'leadership' => rand(1,99),
        'off_the_ball' => rand(1,99),
        'positioning' => rand(1,99),
        'teamwork' => rand(1,99),
        'vision' => rand(1,99),
        'work_rate' => rand(1,99),

        'acceleration' => rand(1,99),
        'agility' => rand(1,99),
        'balance' => rand(1,99),
        'jumping_reach' => rand(1,99),
        'natural_fitness' => rand(1,99),
        'pace' => rand(1,99),
        'stamina' => rand(1,99),
        'strength' => rand(1,99),

        'adaptability' => rand(1,99),
        'consistency' => rand(1,99),
        'dirtiness' => rand(1,99),
        'important_matches' => rand(1,99),
        'injury_proneness' => rand(1,99),
        'versatility' => rand(1,99),
    ];
});
