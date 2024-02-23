<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Examination>
 */
class ExaminationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence($nbWords = 6, $variableNbWords = true),
            'type' => fake()->randomElement(['draft','done']),
            'description' => fake()->text(50),
            'limitedTime' => fake()->numberBetween(15, 60),
            'author' => fake()->name(),
            'thumbnail' => 'http://127.0.0.1:8000/images/course_2.jpg',
            'subjects_id' => fake()->numberBetween(1, 50),
        ];
    }
}
