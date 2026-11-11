<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $name = fake()->words(3, true);

        return [
            'subject_name' => ucfirst($name),
            'credits' => fake()->numberBetween(1, 5),
        ];
    }
}
