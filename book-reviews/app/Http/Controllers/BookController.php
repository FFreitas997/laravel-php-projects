<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Services\BookService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{

    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Search by title
        $title = $request->input('title');

        // Get the size of the pagination
        $size = $request->input('size', 10);

        // Get the filter option
        $filter = $request->input('filter', 'latest');

        // Which page to show
        $page = request()->input('page', 1);

        // Retrieve the books based on information from the request
        $books = $this->service->searchBooks($page, $size, $title, $filter);

        // Return the view with the book data
        return Inertia::render('book/index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(): Response
    {
        // Return the view for creating a new book
        return Inertia::render('book/create');
    }

    /**
     * Store a newly created resource in storage.
     * @param BookRequest $request
     * @return RedirectResponse|null
     */
    public function store(BookRequest $request): ?RedirectResponse
    {
        try {

            // Create a new book with the validated data
            Book::query()->create($request->validated());

            // Redirect to the index page with a success message
            return redirect()->route('books.index')->with('success', 'Book created successfully');
        } catch (Exception $exception) {

            // Log the error message
            Log::error($exception->getMessage());

            // Return to the previous page
            return redirect()->route('books.index')->with('error', 'Failed to create the book.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response|RedirectResponse
    {
        $book = $this->service->retrieveBook($id);

        // Check if the book exists
        if (!$book) {
            return redirect()->route('books.index')->with('error', 'Book with id ' . $id . ' not found.');
        }

        return Inertia::render('book/show', ['book' => $book]);
    }

    /**
     * Destroy the specified resource from storage.
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {

            // Find the book by ID
            $book = Book::query()->findOrFail($id);

            // Check if the book exists
            if (!$book) {
                return redirect()->route('books.index')->with('error', 'Book not found.');
            }

            // Delete the book
            $book->delete();

            // Redirect to the index page with a success message
            return redirect()->route('books.index')->with('success', 'Book deleted successfully.');

        } catch (Exception $exception) {

            // Log the error message
            Log::error($exception->getMessage());

            // Redirect to the index page with an error message
            return redirect()->route('books.index')->with('error', 'Failed to delete the book.');
        }
    }
}
