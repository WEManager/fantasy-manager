<?php

declare(strict_types=1);

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Arena::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->slug,
        'town' => $faker->word,
        'stands_no_roof' => $faker->randomNumber(),
        'stands_roof' => $faker->randomNumber(),
        'seats_no_roof' => $faker->randomNumber(),
        'seats_roof' => $faker->randomNumber(),
        'loges' => $faker->randomNumber(),
    ];
});
