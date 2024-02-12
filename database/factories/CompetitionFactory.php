<?php

namespace Database\Factories;

use Doctrine\Inflector\Rules\Word;
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
            'category_id' => $this->faker->numberbetween(1,10),
            'society_id' => $this->faker->numberbetween(1,10),
            'title' => $this->faker->name(),
            'venue' => $this->faker->word(),
            'description' =>$this->faker->sentence(),
            'minimum_size' =>$this->faker->numberbetween(0,1),
            'maximum_size' =>$this->faker->numberbetween(0,1),
            'image_url' =>$this->faker->url(),
            'date'=>$this->faker->date(),
            'start_at'=> $this->faker->time(),
            'ends_at'=> $this->faker->time(),

            
        ];
    }
}
