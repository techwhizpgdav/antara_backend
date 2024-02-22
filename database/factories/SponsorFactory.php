<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsor>
 */
class SponsorFactory extends Factory
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
            'title' => $this->faker->name(),
            'society_id' => $this->faker->numberbetween(1,10),
            'logo' => $this->faker->url(),
            'company_name' => $this->faker->name(),
            'web_url' => $this->faker->url(),
        ];
    }
}
