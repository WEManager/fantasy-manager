<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\TournamentGame::class, function (Faker $faker) {
    return [
        'group_id' => 1,
        'hometeam_id' => 1,
        'awayteam_id' => 2,
        'start_time' => now()->addDay(1),
        'type' => 1,
        'status' => 0,
    ];
});
