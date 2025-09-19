<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // List tasks with optional filter
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, completed, incomplete

        $query = Task::query();

        if ($filter === 'completed') {
            $query->completed();
        } elseif ($filter === 'incomplete') {
            $query->incomplete();
        }

        $tasks = $query->ordered()->get();

        return view('tasks.index', compact('tasks', 'filter'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskRequest $request)
    {
        // set position to the end
        $max = Task::max('position') ?? 0;
        $task = Task::create(array_merge($request->validated(), ['position' => $max + 1]));

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return view('tasks.create', compact('task'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    // Toggle completion (AJAX-friendly)
    public function toggleComplete(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return response()->json(['ok' => true, 'is_completed' => $task->is_completed]);
    }

    // Reorder tasks: accept array of ids in new order
    public function reorder(Request $request)
    {
        $ids = $request->input('ids'); // expected: [3,1,2,...]

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid payload'], 422);
        }

        foreach ($ids as $index => $id) {
            Task::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['ok' => true]);
    }
}

