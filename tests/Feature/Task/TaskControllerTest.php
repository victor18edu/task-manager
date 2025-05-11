<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_datatable_structure()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tasks = Task::factory()->count(5)->create();

        $user->tasks()->attach($tasks->pluck('id')->toArray());

        $response = $this->getJson('tasks/datatable');

        $response->assertStatus(200);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'status_label',
                        'actions',
                    ]
                ]
            ]);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Tarefa Teste',
            'description' => 'Descrição da tarefa',
            'status' => 'pending',
            'user_id' => $user->id,
        ];

        $response = $this->postJson(route('tasks.store'), $data);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Tarefa criada com sucesso!',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarefa Teste',
            'description' => 'Descrição da tarefa',
        ]);
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $task->users()->sync([$user->id] ?? []);

        $response = $this->getJson(route('tasks.edit', $task->id));

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'user_id' => $task->user_id,
            ]
        ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $task->users()->sync([$user->id] ?? []);

        $updatedData = [
            'title' => 'Nova Tarefa Atualizada',
            'description' => 'Descrição atualizada',
            'status' => 'pending',
        ];

        $response = $this->putJson(route('tasks.update', $task->id), $updatedData);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Tarefa atualizada com sucesso!',
        ]);

        $task->refresh();
        $this->assertEquals($updatedData['title'], $task->title);
        $this->assertEquals($updatedData['description'], $task->description);
        $this->assertEquals($updatedData['status'], $task->status);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $task->users()->sync([$user->id] ?? []);

        $response = $this->deleteJson(route('tasks.destroy', $task->id));

        $response->assertStatus(201);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_mark_as_completed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('tasks.complete', $task->id));

        $response->assertStatus(200);

        $task->refresh();
        $this->assertEquals('completed', $task->status);
    }
}
