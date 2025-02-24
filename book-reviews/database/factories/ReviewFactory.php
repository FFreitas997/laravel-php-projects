<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'book_id' => null,
            'created_at' => fake()->dateTimeBetween('-2 year', 'now'),
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    /**
     * Indicate that the review is good.
     */
    public function good(): ReviewFactory|Factory
    {
        return $this->state(fn(array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
        ]);
    }

    public function average(): ReviewFactory|Factory
    {
        return $this->state(fn(array $attributes) => [
            'rating' => fake()->numberBetween(2, 5),
        ]);
    }

    /**
     * Indicate that the review is bad.
     */
    public function bad(): ReviewFactory|Factory
    {
        return $this->state(fn(array $attributes) => [
            'rating' => fake()->numberBetween(1, 3),
        ]);
    }
}
