<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        $letter = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']);

        return [
            'subject_id'     => Subject::factory(),
            'payload'        => $this->faker->sentence(12) . '?',
            'correct_answer' => $letter,
            'description'    => $this->faker->optional(0.4)->sentence(10),
            'is_active'      => true,
        ];
    }
}
