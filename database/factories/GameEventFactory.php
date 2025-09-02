<?php

declare(strict_types=1);

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\GameEvent::class, function (Faker $faker) {
    return [
        'fixture_id' => factory(App\Models\Fixture::class),
        'event_time' => $faker->dateTime(),
    ];
});
