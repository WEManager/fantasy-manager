<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Club::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->slug,
        'colors' => $faker->word,
        'locale' => $faker->word,
        'reputation' => $faker->randomNumber(),
    ];
});
