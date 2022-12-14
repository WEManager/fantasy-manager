<?php

use App\Models\Club;
use Faker\Generator as Faker;

$factory->define(Club::class, function (Faker $faker) {
    $nations = ['SE', 'ES', 'GB', 'DE', 'IT'];
    
    shuffle($nations);

    return [
        'name' => $faker->name,
        'colors' => '["#1B3E90", "#CC2030", "#FFFFFF"]',
        'locale' => $nations[0],
        'reputation' => $faker->randomNumber(),
    ];
});
