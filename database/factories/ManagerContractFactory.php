<?php

declare(strict_types=1);

/** @var Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\ManagerContract::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'club_id' => 1,
        'wage' => 100,
        'status' => 'ongoing',
        'from' => now(),
        'until' => now()->addDays(30),
    ];
});
