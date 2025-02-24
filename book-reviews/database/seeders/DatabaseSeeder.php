<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Random\RandomException;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        User::factory(10)->create();

        Book::factory(30)->create()->each(function (Book $book) {

            // Generate a random number of reviews for each book
            $numReviews = random_int(5, 30);

            // Create the reviews for the book using the ReviewFactory
            Review::factory()->count($numReviews)
                ->good()
                ->for($book)
                ->create();
        });

        Book::factory(32)->create()->each(function (Book $book) {

            // Generate a random number of reviews for each book
            $numReviews = random_int(5, 30);

            // Create the reviews for the book using the ReviewFactory
            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });

        Book::factory(34)->create()->each(function (Book $book) {

            // Generate a random number of reviews for each book
            $numReviews = random_int(5, 30);

            // Create the reviews for the book using the ReviewFactory
            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });

    }
}
