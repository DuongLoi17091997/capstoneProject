<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamationHistories>
 */
class ExamationHistoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => '51',
            'result' => fake()->randomElement(['passed','failed']),
            'comments' => fake()->sentence($nbWords = 8, $variableNbWords = true),
            'time_for_completed' => fake()->numberBetween(1, 60),
            'examination_id' => fake()->numberBetween(1, 20),
            'score' => fake()->numberBetween(1, 100),
        ];
    }
}
