<?php

declare(strict_types=1);

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Lineup::class, function (Faker $faker) {
    $team = ['senior', 'u21', 'u19'];
    shuffle($team);

    return [
        'club_id' => null,
        'team' => $team[0],
    ];
});
