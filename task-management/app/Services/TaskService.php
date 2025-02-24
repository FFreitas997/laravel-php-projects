<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class TaskService
 * <p>Task service contains all CRUD operations for management</p>
 * <p>You should visit the following URL to learn more about Laravel Query Builder: <a href="https://laravel.com/docs/11.x/queries">Query Builder</a></p>
 */
class TaskService
{
    public function getTaskByID(int $id): Task
    {
        return Task::query()->findOrFail($id);
    }

    public function getTasks(int $size = 10, string $search = null, bool $completed = false): LengthAwarePaginator
    {
        $builder = Task::query();

        if ($search) {
            $builder = $builder->whereAny(['title'], 'like', "%$search%");
        }

        if ($completed) {
            $builder = $builder->where('completed', true);
        }

        return $builder
            ->latest('updated_at')
            ->paginate($size);
    }

    public function createTask(array $data): int
    {
        $task = Task::query()->create($data);

        return $task->id;
    }

    public function updateTask(int $id, array $data): void
    {
        $task = Task::query()->findOrFail($id);

        $task->update($data);
    }

    public function deleteTask(int $id): void
    {
        $task = Task::query()->findOrFail($id);

        $task->delete();
    }
}
