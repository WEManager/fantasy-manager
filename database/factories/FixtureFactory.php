<?php

declare(strict_types=1);

use App\Enums\FixtureStatus;
use App\Enums\FixtureType;
use App\Models\Club;
use App\Models\Fixture;
use App\Models\TournamentGroup;
use Faker\Generator as Faker;

$factory->define(Fixture::class, function (Faker $faker) {
    return [
        'group_id' => factory(TournamentGroup::class),
        'hometeam_id' => factory(Club::class),
        'awayteam_id' => factory(Club::class),
        'hometeam_score' => $faker->numberBetween(0, 5),
        'awayteam_score' => $faker->numberBetween(0, 5),
        'start_time' => $faker->dateTimeBetween('now', '+1 month'),
        'type' => $faker->randomElement(FixtureType::cases()),
        'status' => $faker->randomElement(FixtureStatus::cases()),
    ];
});
