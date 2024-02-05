<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competition>
 */
class CompetitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'categor_id' => $this->faker->numberbetween(1,10),
            'society_id' => $this->faker->numberbetween(1,10),
            'title' => $this->faker->name(),
            'logo' => $this->faker->url(),
        ];
    }
}
