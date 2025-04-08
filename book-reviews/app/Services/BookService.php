<?php

namespace App\Services;

use App\Models\Book;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class BookService
{
    private int $cacheExpiration = 300; // Cache expiration time in seconds
    private string $cacheServicePrefix = 'books-service-cache:';

    /**
     * Search for books based on the title, size, and filter.
     *
     * @param int $page The page number to retrieve.
     * @param string|null $title The title to search for.
     * @param int $size The number of items per page.
     * @param string $filter The filter option to apply.
     * @return LengthAwarePaginator A paginator instance containing the results.
     */
    public function searchBooks(int $page = 1, int $size = 10, string $title = null, string $filter = 'latest'): LengthAwarePaginator
    {
        try {


            // generate a cache key based on the title, size, and page
            $cacheKey = $this->cacheServicePrefix . md5("search:{$filter}_{$title}_{$size}_{$page}");

            // Check if the cache is already set for the given key
            return cache()->remember($cacheKey, $this->cacheExpiration, function () use ($size, $filter, $title) {

                // Get Book query builder
                $query = Book::query();

                // Retrieve the books based on the title if provided
                $query = $query->when($title, fn($query, $title) => $query->title($title));

                // Apply the filter based on the selected option
                $query = match ($filter) {
                    'popular_last_month' => $query->popularLastMonth(),
                    'popular_last_6month' => $query->popularLast6Months(),
                    'highest_rated_last_month' => $query->highestRatedLastMonth(),
                    'highest_rated_last_6month' => $query->highestRatedLast6Months(),
                    default => $query->latest()->withAvgRating()->withReviewsCount()
                };

                // Paginate the results
                return $query->paginate($size);
            });

        } catch (Exception $exception) {

            // Log the error message
            Log::error($exception->getMessage());

            // Return an empty paginator
            return new LengthAwarePaginator([], 0, $size, $page);
        }
    }

    /**
     * Retrieve a book by its ID.
     *
     * @param int $id The ID of the book to retrieve.
     * @return Book|null The retrieved book or null if not found.
     */
    public function retrieveBook(int $id): ?Book
    {
        try {

            // Check if the ID is valid
            if (!is_numeric($id) || $id <= 0) {
                return null;
            }

            // Generate a cache key based on the book ID
            $cacheKey = $this->cacheServicePrefix . md5("book-id:{$id}");

            // Check if the book is already cached
            return cache()->remember($cacheKey, $this->cacheExpiration, function () use ($id) {

                // Get Book query builder
                $query = Book::query();

                //Average review rating
                $query = $query->withAvgRating();

                //Number of reviews
                $query = $query->withReviewsCount();

                // Latest Reviews
                $query = $query->with(['reviews' => fn($query) => $query->latest()]);

                // Find the book by ID
                return $query->findOrFail($id);
            });

        } catch (Exception $exception) {

            // Log the error message
            Log::error($exception->getMessage());

            // Return null if the book is not found
            return null;
        }
    }
}
