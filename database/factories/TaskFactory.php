<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'created_by' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TaskStatus::cases())->value,
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
