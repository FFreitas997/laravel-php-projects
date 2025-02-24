<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(7, true),
            'completed' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the task is incomplete.
     *
     * @return TaskFactory
     */
    public function incomplete(): static
    {
        return $this->state(fn(array $attributes) => [
            'completed' => false,
        ]);
    }
}
