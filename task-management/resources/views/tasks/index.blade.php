@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 min-h-screen flex items-center justify-center p-6 dark:bg-gray-800">
        <div class=" w-full space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold dark:text-white">Tasks Management</h1>
                <a href="{{ route('tasks.create')  }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-2xl shadow hover:bg-blue-700">
                    Create Task
                </a>
            </div>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden dark:bg-gray-900">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-200 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-start dark:text-white">Title</th>
                        <th class="px-4 py-3 text-center dark:text-white">Completed</th>
                        <th class="px-4 py-3 text-center dark:text-white">Created At</th>
                        <th class="px-4 py-3 text-center dark:text-white">Updated At</th>
                        <th class="px-4 py-3 text-end dark:text-white">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($tasks as $task)
                        <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-start dark:text-white">{{ $task->title  }}</td>
                            <td class="px-4 py-3 text-center dark:text-white">
                                @if($task->completed)
                                    <span class="material-icons text-base text-green-500">check_circle</span>
                                @endif
                                @if(!$task->completed)
                                    <span class="material-icons text-base text-red-500">cancel</span>
                                @endif

                            </td>
                            <td class="px-4 py-3 text-center dark:text-white">{{ $task->created_at  }}</td>
                            <td class="px-4 py-3 text-center dark:text-white">{{ $task->updated_at  }}</td>
                            <td class="px-4 py-3 flex flex-row justify-end items-center gap-3">
                                <a href="{{ route('tasks.show', [ 'id' => $task->id ])  }}"
                                   class="bg-indigo-500 text-white px-2 py-1 rounded-full hover:bg-indigo-600 flex items-center justify-center"
                                   title="Details">
                                    <span class="material-icons text-lg">info</span>
                                </a>
                                <a href="{{ route('tasks.edit', [ 'id' => $task->id ])  }}"
                                   class="bg-green-500 text-white px-2 py-1 rounded-full hover:bg-green-600 flex items-center justify-center"
                                   title="Edit">
                                    <span class="material-icons text-lg">edit</span>
                                </a>
                                <form action="{{ route('tasks.delete', [ 'id' => $task->id ]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="bg-red-500 text-white px-2 py-1 rounded-full hover:bg-red-600 flex items-center justify-center">
                                        <span class="material-icons text-lg">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="w-full px-4 py-6 text-center text-gray-500 dark:text-white">No tasks found.</td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
                @if($tasks->count())
                    <div class="flex justify-end items-center p-4 border-t bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        {{ $tasks->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
