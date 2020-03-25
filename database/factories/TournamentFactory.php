<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tournament;
use Faker\Generator as Faker;

$factory->define(Tournament::class, function (Faker $faker) {
    $nations = ['SE', 'ES', 'GB', 'DE', 'IT'];
    shuffle($nations);

    $type = ['league', 'groups'];
    shuffle($type);

    $groups = 1;
    if ($type[0] == 'groups') {
        $groups = rand(2, 4);
    }

    return [
        'name' => $faker->country . ' ' . $faker->name,
        'nationality' => $nations[0],
        'recurring_every_of_year' => 1,
        'participants' => 8,
        'type' => $type[0],
        'groups' => $groups,
        'playoffs' => 0,
        'team' => 'senior',
    ];
});
