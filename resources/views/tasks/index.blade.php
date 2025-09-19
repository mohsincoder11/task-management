@extends('layouts.app')

@section('content')
    <div class="d-flex mb-3">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary me-auto">Create Task</a>

        <form class="d-flex" method="get" action="{{ route('tasks.index') }}">
            <select name="filter" class="form-select me-2" onchange="this.form.submit()">
                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
                <option value="completed" {{ $filter === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="incomplete" {{ $filter === 'incomplete' ? 'selected' : '' }}>Incomplete</option>
            </select>
            <noscript><button class="btn btn-secondary">Filter</button></noscript>
        </form>
    </div>

    <ul id="task-list" class="list-group">
        @forelse($tasks as $task)
            <li class="list-group-item d-flex align-items-start" data-id="{{ $task->id }}">
                <div class="form-check me-3">
                    <input class="form-check-input toggle-complete" type="checkbox" data-id="{{ $task->id }}"
                        {{ $task->is_completed ? 'checked' : '' }}
                        title="{{ $task->is_completed ? 'Mark as incomplete' : 'Mark as complete' }}"
                        data-bs-toggle="tooltip" data-bs-placement="top">
                </div>


                <div class="flex-grow-1">
                    <div class="fw-bold {{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                        {{ $task->title }}</div>
                    <div class="small text-muted">{{ $task->description }}</div>
                </div>

                <div class="ms-3">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline"
                        onsubmit="return confirm('Delete task?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </li>
        @empty
            <div class="flex-grow-1 ">
                <p class="text-muted"> <strong> No task present
                    </strong></p>
            </div>
        @endforelse

    </ul>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle complete
        document.querySelectorAll('.toggle-complete').forEach(cb => {
            cb.addEventListener('change', async function() {
                const id = this.dataset.id;
                await fetch(`/tasks/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    }
                });
                location.reload(); // simple approach: refresh so UI classes update
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });

        // Reordering using SortableJS
        new Sortable(document.getElementById('task-list'), {
            animation: 150,
            onEnd: async function(evt) {
                const ids = Array.from(document.querySelectorAll('#task-list > li')).map(li => li.dataset.id);
                await fetch('/tasks/reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        ids
                    })
                });
            }
        });
    </script>
@endpush
