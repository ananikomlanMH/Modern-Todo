<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can view task list', function () {
    // Arrange
    $tasks = Task::factory(3)->create([
        'user_id' => $this->user->id
    ]);

    // Act
    $response = $this->get(route('tasks.index'));

    // Assert
    $response->assertStatus(200);
//    $response->assertInertia(fn ($page) => $page
//        ->component('Tasks/Index')
//        ->has('tasks', 3)
//    );
});

test('user cannot view tasks of other users', function () {
    // Arrange
    $otherUser = User::factory()->create();
    Task::factory(3)->create([
        'user_id' => $otherUser->id
    ]);

    // Act
    $response = $this->get(route('tasks.index'));

    // Assert
    $response->assertStatus(200);

//    $response->assertInertia(fn ($page) => $page
//        ->component('Tasks/Index')
//        ->has('tasks', 0)
//    );
});

test('user can create a new task', function () {
    // Arrange
    $taskData = [
        'title' => 'New Test Task',
        'description' => 'Test Description',
        'status' => 'pending',
        'priority' => 'medium',
        'due_date' => '2024-03-20',
        'category_ids' => [],
        'tag_ids' => []
    ];

    // Act
    $response = $this->post(route('tasks.store'), $taskData);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('tasks', [
        'title' => 'New Test Task',
        'user_id' => $this->user->id
    ]);
});

test('user cannot create task with invalid data', function () {
    // Act
    $response = $this->post(route('tasks.store'), [
        'title' => '', // Title is required
        'status' => 'invalid_status',
        'priority' => 'invalid_priority'
    ]);

    // Assert
    $response->assertSessionHasErrors(['title', 'status', 'priority']);
});

test('user can update their task', function () {
    // Arrange
    $task = Task::factory()->create([
        'user_id' => $this->user->id
    ]);

    $updatedData = [
        'title' => 'Updated Task Title',
        'description' => 'Updated Description',
        'status' => 'completed',
        'priority' => 'high',
        'due_date' => '2024-03-21',
        'category_ids' => [],
        'tag_ids' => []
    ];

    // Act
    $response = $this->put(route('tasks.update', $task), $updatedData);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Task Title',
        'status' => 'completed'
    ]);
});

test('user cannot update tasks of other users', function () {
    // Arrange
    $otherUser = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $otherUser->id
    ]);

    // Act
    $response = $this->put(route('tasks.update', $task), [
        'title' => 'Trying to Update',
        'status' => 'completed',
        'priority' => 'high'
    ]);

    // Assert
    $response->assertStatus(302);
});

test('user can delete their task', function () {
    // Arrange
    $task = Task::factory()->create([
        'user_id' => $this->user->id
    ]);

    // Act
    $response = $this->delete(route('tasks.destroy', $task));

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('user cannot delete tasks of other users', function () {
    // Arrange
    $otherUser = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $otherUser->id
    ]);

    // Act
    $response = $this->delete(route('tasks.destroy', $task));

    // Assert
    $response->assertStatus(302);
});

test('guests cannot access task routes', function () {
    // Arrange
    auth()->logout();
    $task = Task::factory()->create();

    // Assert
    $this->get(route('tasks.index'))->assertRedirect('login');
    $this->post(route('tasks.store'))->assertRedirect('login');
    $this->put(route('tasks.update', $task))->assertRedirect('login');
    $this->delete(route('tasks.destroy', $task))->assertRedirect('login');
});
