<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'rating'];

    /**
     * The book that the review belongs to.
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * The boot method to clear the cache when a review is created, updated, or deleted.
     * @return void
     */
    protected static function booted(): void
    {
        static::created(static fn (Review $review) => cache()->forget('books-service-cache:' . md5("book-id:{$review->book_id}")));
        static::updated(static fn (Review $review) => cache()->forget('books-service-cache:' . md5("book-id:{$review->book_id}")));
        static::deleted(static fn (Review $review) => cache()->forget('books-service-cache:' . md5("book-id:{$review->book_id}")));
    }
}
