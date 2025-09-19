<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $this->post('/tasks', ['title' => 'Test', 'description' => 'x'])
            ->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['title' => 'Test']);
    }

    public function test_can_toggle_task()
    {
        $task = Task::create(['title' => 'T', 'position' => 0]);
        $this->post("/tasks/{$task->id}/toggle");
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);
    }
}
