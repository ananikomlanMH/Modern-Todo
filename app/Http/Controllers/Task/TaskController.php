<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['categories', 'tags', 'subtasks'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
        ]);

        $task = Task::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        if (isset($validated['category_ids'])) {
            $task->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['tag_ids'])) {
            $task->tags()->sync($validated['tag_ids']);
        }

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
        ]);

        $task->update($validated);

        if (isset($validated['category_ids'])) {
            $task->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['tag_ids'])) {
            $task->tags()->sync($validated['tag_ids']);
        }

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }
}
