<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subjects>
 */
class SubjectsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Math', 'English','Biology','Chemistry','Computer science','Algebra','Geometry','Literature','History','Geography']),
            'range' => fake()->randomElement(['Easy', 'Normal','Hard','Challenging']),
            'grades_id' => fake()->randomElement([1,2,3,4,5,6,7,8,9,10,11,12])
        ];
    }
}
