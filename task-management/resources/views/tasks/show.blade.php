@extends('layouts.app')

@section('content')

    <div class="bg-gray-100 min-h-screen flex items-center justify-center p-6 dark:bg-gray-800">
        <div class="max-w-4xl w-full space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold dark:text-white">Task Details</h1>
                <a href="{{ route('tasks.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-2xl shadow hover:bg-gray-600 flex items-center dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    <span class="material-icons mr-1">arrow_back</span> Back to Tasks
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6 dark:bg-gray-900">
                <div class="flex justify-between items-center border-b pb-4">
                    <h2 class="text-2xl font-semibold dark:text-white">Title: <span class="font-normal">{{ $task->title }}</span></h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white" style="background-color: #10B981;">
                        <span class="material-icons text-base mr-1">{{ $task->completed ? 'check_circle' : 'cancel' }}</span>&nbsp;Completed
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold dark:text-white">Description</h3>
                        <p class="text-gray-700 dark:text-white">{{$task->description}}</p>
                    </div>

                    @if($task->long_description)
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white">Long Description</h3>
                            <p class="text-gray-700 leading-relaxed dark:text-white">{{$task->long_description}}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white">Created At</h3>
                            <p class="text-gray-700 dark:text-white">{{ $task->created_at }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white">Updated At</h3>
                            <p class="text-gray-700 dark:text-white">{{ $task->updated_at }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('tasks.edit', [ 'id' => $task->id ])  }}"
                       class="bg-green-500 text-white px-4 py-2 rounded-2xl shadow hover:bg-green-600 flex items-center"
                       title="Edit Task">
                        <span class="material-icons mr-1">edit</span> Edit
                    </a>
                    <form action="{{ route('tasks.delete', [ 'id' => $task->id ]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-2xl shadow hover:bg-red-600 flex items-center">
                            <span class="material-icons mr-1">delete</span> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
@endsection
