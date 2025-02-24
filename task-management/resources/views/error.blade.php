@extends('layouts.app')

@section('title', 'Something went wrong')

@section('content')
    <div class="bg-gray-100 min-h-screen flex items-center justify-center flex-col p-6 dark:bg-gray-800" >
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-8 text-center space-y-6 dark:bg-gray-900">
            <div class="flex justify-center">
                <span class="material-icons text-red-500 text-6xl">error_outline</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">An Error Occurred</h1>
            <p class="text-gray-600 dark:text-white">Something went wrong while processing your request. Please try again later or
                contact support if the issue persists.</p>

            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm dark:bg-red-300 dark:text-red-900" role="alert">
                <strong>Error Message:</strong> {{ $message }}
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('tasks.index') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-2xl shadow hover:bg-blue-700 flex items-center dark:bg-blue-700 dark:text-white dark:hover:bg-blue-800"
                   title="Go Back">
                    <span class="material-icons mr-1">arrow_back</span> Go Back
                </a>
                <a href="{{ route('tasks.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-2xl shadow hover:bg-gray-600 flex items-center dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                   title="Home">
                    <span class="material-icons mr-1">home</span> Home
                </a>
            </div>
        </div>
    </div>
@endsection
