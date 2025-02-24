@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 min-h-screen flex items-center justify-center flex-col p-6 dark:bg-gray-800" >
        <h1 class="text-2xl text-center m-3 dark:text-white">Create Task</h1>
        <div class="w-full max-w-lg flex justify-center items-center border-1 border-sky-500 rounded-xl shadow-lg p-4 dark:bg-gray-900">
        <form class="w-full" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="title">Title</label>
                <input value="{{ old('title') }}" placeholder="Task Example" type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('title')
                <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="description">Description</label>
                <textarea name="description" id="description" rows="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description') }}</textarea>
                @error('description')
                <div class="text-red-600 text-sm">{{ $message  }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="long_description">Long Description</label>
                <textarea name="long_description" id="long_description" rows="10" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('long_description') }}</textarea>
                @error('long_description')
                <div class="text-red-600 text-sm">{{ $message  }}</div>
                @enderror
            </div>

            <div class="flex justify-center items-center">
                <button class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" type="submit">Submit</button>
            </div>

        </form>
    </div>
    </div>
@endsection
