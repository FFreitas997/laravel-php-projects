<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     * @param Book $book
     * @return Response
     */
    public function create(Book $book): Response
    {
        // Return the view for creating a new review for a book
        return Inertia::render('review/create', ['book' => $book]);
    }

    /**
     * Store a newly created review in storage.
     * @param ReviewRequest $request
     * @param Book $book
     * @return RedirectResponse|null
     */
    public function store(ReviewRequest $request, Book $book): ?RedirectResponse
    {
        try {

            // Create a new review for the book
            $book->reviews()->create($request->validated());

            // Redirect to the book's page with a success message
            return redirect()->route('books.show', $book->id)->with('success', 'Review created successfully.');

        } catch (Exception $exception) {

            // Log the exception
            Log::error('Error creating review: ' . $exception->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while creating the review.');
        }
    }
}
