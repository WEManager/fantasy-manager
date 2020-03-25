<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\GameEvent::class, function (Faker $faker) {
    return [
        'game_id' => factory(App\TournamentGame::class),
        'event_time' => $faker->dateTime(),
    ];
});
