<?php

namespace Database\Factories;

use App\Models\Society;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Society>
 */
class SocietyFactory extends Factory
{

    protected $model = Society::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'logo' => $this->faker->url(),
            'description' => $this->faker->sentence()
        ];
    }
}
