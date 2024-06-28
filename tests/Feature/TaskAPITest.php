<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskAPITest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_user_create_new_task()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'user_id' => $this->user->id,
            'titre' => 'Titre Task',
            'description' => 'Description Task',
            'statut' => 'active',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(201)->assertJson([
            'data' => [
                'id' => '1',
                'user' => $this->user->name,
                'titre' => 'Titre Task',
                'description' => 'Description Task',
                'statut' => 'active',
                'date_dech' => '2024-06-28',
            ],
        ]);
    }

    public function test_admin_create_task()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/tasks', [
            'user_id' => $this->admin->id,
            'titre' => 'New Task',
            'description' => 'Task Description',
            'statut' => 'active',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(201)->assertJson([
            'data' => [
                'id'    => '1',
                'user' => $this->admin->name,
                'titre' => 'New Task',
                'description' => 'Task Description',
                'statut' => 'active',
                'date_dech' => '2024-06-28',
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);
    }

    public function test_user_or_admin_create_new_task_validation_errors(){
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'titre' => '',
            'description' => '',
            'date_dech' => '',
        ]);
        $response->assertStatus(401);
        $response->assertJsonValidationErrors(['titre', 'description', 'date_dech']);
    }

    public function test_user_can_show_the_task_dtails_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);
    }

    public function test_user_cannot_show_task_dtails_not_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(403);
    }

    public function test_admin_sow_all_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $task_admin = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->admin)->getJson("/api/tasks/{$task_admin->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);
    }

    public function test_user_update_the_task_that_belongs_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task',
            'description' => 'Updated Description',
            'statut' => 'active',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);
    }

    public function test_user_cannot_update_task_not_belong_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task',
            'description' => 'Updated Description',
            'statut' => 'inactive',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_can_show_update_all_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $task_admin = Task::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task User',
            'description' => 'Updated Description User',
            'statut' => 'inactive',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);

        $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$task_admin->id}", [
            'titre' => 'Updated Task User',
            'description' => 'Updated Description User',
            'statut' => 'active',
            'date_dech' => '2024-06-28',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'titre',
                'description',
                'statut',
                'date_dech',
            ],
        ]);
    }

    public function test_user_destroy_the_task_that_belongs_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(201);
    }

    public function test_admin_destroy_your_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(201);
    }

    public function test_admin_destroy_all_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(201);
    }

    public function test_user_destroy_task_not_belong_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(403);
    }

    public function test_user_cannot_restore_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response = $this->actingAs($this->user)->get("/api/tasks/deleted");
        $response->assertStatus(500);
    }

}
