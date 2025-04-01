<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws ValidationException
     */
    public function index(Request $request): Response
    {
        // Initialize an empty array for books
        $books = [];

        try {

            // Validate the request parameters
            $request->validate(['title' => 'nullable|string|max:255']);

            // Get the title and size from the request
            $title = $request->input('title');
            $size = $request->input('size', 10);

            // Check if the title is not empty
            $books = Book::query()
                ->when($title, fn($query, $title) => $query->title($title))
                ->paginate($size);

        } catch (Exception $exception) {
            // Log the error message
            Log::error($exception->getMessage());
        }

        // Return the view with the books data
        return Inertia::render('book/index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
