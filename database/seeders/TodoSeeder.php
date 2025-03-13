<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Subtask;
use App\Models\Attachment;
use App\Models\Reminder;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 users with their tasks and related data
        User::factory(5)->create()->each(function ($user) {
            // Create 5 categories per user
            $categories = Category::factory(5)->create([
                'user_id' => $user->id,
            ]);

            // Create 10 tags per user
            $tags = Tag::factory(10)->create([
                'user_id' => $user->id,
            ]);

            // Create 20 tasks per user
            Task::factory(20)->create([
                'user_id' => $user->id,
            ])->each(function ($task) use ($categories, $tags) {
                // Attach random categories (1-3) to each task
                $task->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Attach random tags (0-5) to each task
                $task->tags()->attach(
                    $tags->random(rand(0, 5))->pluck('id')->toArray()
                );

                // Create 0-5 subtasks per task
                Subtask::factory(rand(0, 5))->create([
                    'task_id' => $task->id,
                ]);

                // Create 0-3 attachments per task
                Attachment::factory(rand(0, 3))->create([
                    'task_id' => $task->id,
                ]);

                // Create 0-2 reminders per task
                Reminder::factory(rand(0, 2))->create([
                    'task_id' => $task->id,
                ]);
            });
        });
    }
}