<?php

declare(strict_types=1);

use App\Enums\TournamentType;
use App\Models\Tournament;
use Faker\Generator as Faker;

$factory->define(Tournament::class, function (Faker $faker) {
    $types = [TournamentType::LEAGUE, TournamentType::GROUPS];
    shuffle($types);
    $selectedType = $types[0];

    $groups = 1;
    if ($selectedType === TournamentType::GROUPS) {
        $groups = rand(2, 4);
    }

    return [
        'name' => $faker->country . ' ' . $faker->name,
        'recurring_every_of_year' => 1,
        'participants' => 8,
        'type' => $selectedType,
        'groups' => $groups,
        'playoffs' => 0,
        'team' => 'senior',
    ];
});
