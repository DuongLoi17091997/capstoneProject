<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Questions>
 */
class QuestionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titles' => fake()->text,
            'type' => fake()->randomElement(['multiple','written']),
            'a_seletion' => fake()->text,
            'b_seletion' => fake()->text,
            'c_seletion' => fake()->text,
            'd_seletion' => fake()->text,
            'multiple_seletion_result' => fake()->text,
            'writing_result' => fake()->text,
            'subjects_id' => fake()->numberBetween(1, 50),
            'status' => '1'
        ];
    }
}
