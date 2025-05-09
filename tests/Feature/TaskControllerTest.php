<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_index_returns_tasks()
    {
        $this->authenticate();
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'tasks',
                'current_page',
                'total_pages',
                'total_tasks',
            ]);
    }

    public function test_store_creates_task()
    {
        $this->authenticate();
    
        $assignedUser = User::factory()->create();
    
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'todo',
            'priority' => 'low',
            'order' => 1,
            'user_id' => $assignedUser->user_id, 
        ];
    
        $response = $this->postJson('/api/tasks', $taskData);
    
        $response->assertStatus(201)
            ->assertJson(['success' => 1]);
    
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }
    

    public function test_update_modifies_task()
    {
        $user = $this->authenticate();
          
        $assignedUser = User::factory()->create(); 
        $task = Task::factory()->create(['created_by' => $user->user_id]);
    
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Task description',
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'done',
            'priority' => 'low',
            'order' => 1,
            'user_id' => $assignedUser->user_id, 
        ];
    
        $response = $this->putJson("/api/tasks/{$task->task_id}", $updatedData);
    
        $response->assertStatus(201)
            ->assertJson(['success' => 1]);
    
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Title']);
    }
    

    public function test_destroy_deletes_task()
    {
        $user = $this->authenticate(); 
        $task = Task::factory()->create(['created_by' => $user->user_id]); 
    
        $response = $this->deleteJson("/api/tasks/{$task->task_id}");
    
        $response->assertStatus(200)
            ->assertJson(['success' => 1]);
        
        $this->assertSoftDeleted('tasks', ['task_id' => $task->task_id]);
    }

    public function test_get_users_returns_users()
    {
        $this->authenticate();
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([['user_id', 'name', 'email']]);
    }
}
