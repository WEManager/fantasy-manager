<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Federation::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'locale' => $faker->word,
    ];
});
