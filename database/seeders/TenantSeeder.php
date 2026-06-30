<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::factory(3)->create()->each(function (Tenant $tenant) {
            $users = User::factory(2)->create(['tenant_id' => $tenant->id]);

            $users->each(function (User $user) use ($tenant) {
                Task::factory(5)->create([
                    'tenant_id' => $tenant->id,
                    'created_by' => $user->id,
                ]);
            });
        });
    }
}
