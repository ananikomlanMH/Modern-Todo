<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'filename' => fake()->word() . '.' . fake()->fileExtension(),
            'path' => fake()->filePath(),
            'type' => fake()->mimeType(),
            'size' => fake()->numberBetween(1000, 5000000),
        ];
    }
}
