<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        $nations = ['SE', 'ES', 'GB', 'DE', 'IT'];
        
        shuffle($nations);

        return [
            'name' => $this->faker->company,
            'colors' => '["#1B3E90", "#CC2030", "#FFFFFF"]',
            'locale' => $nations[0],
            'reputation' => $this->faker->randomNumber(),
        ];
    }
}
