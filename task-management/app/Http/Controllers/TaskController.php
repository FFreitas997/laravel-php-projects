<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(): Factory|View|Application|RedirectResponse
    {
        try {
            return redirect()->route('tasks.index');
        } catch (Exception $e) {
            Log::error('Error showing all completed tasks', ['error' => $e->getMessage()]);
            return view('error', ['message' => '404 Not Found']);
        }

    }

    public function showCreate(): Application|Factory|View
    {
        try {
            return view('tasks.create');
        } catch (Exception $e) {
            Log::error('Error showing create task', ['error' => $e->getMessage()]);
            return view('error', ['message' => '404 Not Found']);
        }
    }

    public function createTask(TaskRequest $request): RedirectResponse
    {
        try {

            Log::info('Creating a new task');

            $data = $request->validated();

            $id = $this->service->createTask($data);

            return redirect()->route('tasks.show', ['id' => $id])->with('success', 'Task created successfully !');

        } catch (Exception $e) {
            Log::error('Error creating a new task', ['error' => $e->getMessage()]);
            return redirect()->route('tasks.create')->with('error', 'Error creating a new task');
        }
    }

    public function showAllCompletedTasks(): View
    {
        $tasks = [];
        try {

            $tasks = $this->service->getTasks();

        } catch (Exception $e) {
            Log::error('Error showing all completed tasks', ['error' => $e->getMessage()]);
        }
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function showTask(int $id): Factory|View|Application|RedirectResponse
    {
        try {
            $task = $this->service->getTaskByID($id);

            return view('tasks.show', ['task' => $task]);
        } catch (Exception $e) {
            Log::error('Error showing task', ['error' => $e->getMessage()]);
            return redirect()->route('tasks.index')->with('error', 'Error showing task');
        }
    }

    public function updateTask(int $id, TaskRequest $request): RedirectResponse
    {
        try {
            Log::info('Updating task with id: ' . $id);

            $data = $request->validated();

            $this->service->updateTask($id, $data);

            return redirect()->route('tasks.show', ['id' => $id])->with('success', 'Task updated successfully !');
        } catch (Exception $e) {
            Log::error('Error updating task', ['error' => $e->getMessage()]);
            return redirect()->route('tasks.edit', ['id' => $id])->with('error', 'Error updating task');
        }

    }

    public function showEdit($id): Factory|View|Application|RedirectResponse
    {
        try {
            $task = $this->service->getTaskByID($id);

            return view('tasks.edit', ['task' => $task]);
        } catch (Exception $e) {
            Log::error('Error showing edit task', ['error' => $e->getMessage()]);
            return redirect()->route('tasks.edit', ['id' => $id])->with('error', 'Error showing edit task');
        }
    }

    public function deleteTask(int $id): RedirectResponse
    {
        try {
            Log::info('Deleting task with id: ' . $id);

            $this->service->deleteTask($id);

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully !');
        } catch (Exception $e) {
            Log::error('Error deleting task', ['error' => $e->getMessage()]);
            return redirect()->route('tasks.index')->with('error', 'Error deleting task');
        }
    }

    public function fallback(): Application|Factory|View
    {
        return view('error', ['message' => '404 Not Found']);
    }
}
