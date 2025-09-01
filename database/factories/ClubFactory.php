<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ClubFactory extends Factory
{
    protected $model = Club::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'colors' => '["#1B3E90", "#CC2030", "#FFFFFF"]',
            'reputation' => $this->faker->randomNumber(),
        ];
    }
}
