<?php

namespace Database\Factories;

use App\Models\Round;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Round>
 */
class RoundFactory extends Factory
{

    protected $model = Round::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'competition_id' => 1,
            'name' => $this->faker->randomElement(['priliminary', 'final']),
            'mode' => $this->faker->randomElement(['online', 'offline']),
        ];
    }
}
