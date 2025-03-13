<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'reminder_time' => fake()->dateTimeBetween('now', '+30 days'),
            'is_sent' => fake()->boolean(),
        ];
    }
}