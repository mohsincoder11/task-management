@extends('layouts.app')
@section('content')
@php($isEdit = isset($task))

<h2>
    @if($isEdit)
    Edit Task
    @else
     Create Task
    @endif
    </h2>
<form method="POST" action="{{ $isEdit ? route('tasks.update', $task) : route('tasks.store') }}">
    @csrf
    @if($isEdit)
    @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" value="{{ old('title', $task->title ?? '') }}" class="form-control" required>
        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection