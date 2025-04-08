<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author'];

    /**
     * The reviews that belong to the book.
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The scope to filter books by title.
     * @param Builder $query
     * @param string $title
     * @return Builder
     */
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    /**
     * The scope to include the reviews count.
     * @param Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return Builder
     */
    public function scopeWithReviewsCount(Builder $query, string $from = null, string $to = null): Builder
    {
        return $query->withCount(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)]);
    }

    /**
     * The scope to include the average rating of reviews.
     * @param Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return Builder
     */
    public function scopeWithAvgRating(Builder $query, string $from = null, string $to = null): Builder
    {
        return $query->withAvg(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)], 'rating');
    }

    /**
     * The scope to get the books with the most reviews (popular) within a date range.
     * @param Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return Builder
     */
    public function scopePopular(Builder $query, string $from = null, string $to = null): Builder
    {
        return $query
            ->withReviewsCount($from, $to)
            ->orderBy('reviews_count', 'desc');
    }

    /**
     * The scope to get the highest rated books within a date range.
     * @param Builder $query
     * @param string|null $from
     * @param string|null $to
     * @return Builder
     */
    public function scopeHighestRated(Builder $query, string $from = null, string $to = null): Builder
    {
        return $query
            ->withAvgRating($from, $to)
            ->orderBy('reviews_avg_rating', 'desc');
    }

    /**
     * The scope to filter books by minimum number of reviews.
     * @param Builder $query
     * @param int $minReviews
     * @return Builder
     */
    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    /**
     * The scope to get popular books from the last month.
     * @param Builder $query
     * @return Builder
     */
    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query
            ->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    /**
     * The scope to get popular books from the last 6 months.
     * @param Builder $query
     * @return Builder
     */
    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query
            ->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    /**
     * The scope to get highest rated books from the last month.
     * @param Builder $query
     * @return Builder
     */
    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query
            ->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    /**
     * The scope to get highest rated books from the last 6 months.
     * @param Builder $query
     * @return Builder
     */
    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query
            ->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }

    /**
     * Utility function to filter by date range.
     * @param Builder $query
     * @param $from
     * @param $to
     * @return void
     */
    private function dateRangeFilter(Builder $query, $from = null, $to = null): void
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    /**
     * The event that is triggered when a book is updated or deleted.
     * @return void
     */
    protected static function booted(): void
    {
        static::updated(static fn(Book $book) => cache()->forget('books-service-cache:' . md5("book-id:{$book->id}")));
        static::deleted(static fn(Book $book) => cache()->forget('books-service-cache:' . md5("book-id:{$book->id}")));
    }
}
