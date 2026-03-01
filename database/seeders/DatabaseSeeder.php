<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Database\Seeders;

use Illuminate\Database\Seeder;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Models\UserProfile;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->first();

        if ( ! $tenant) {
            $this->command?->error('Tenants not found. Please run TenantSeeder first.');
            return;
        }

        $tenant->makeCurrent();

        $this->seedUserProfiles($tenant);
    }

    private function seedUserProfiles(Tenant $tenant): void
    {
        $users = User::query()->get();

        $createdCount = 0;
        $existingCount = 0;

        foreach ($users as $userData) {
            $userProfile = UserProfile::query()
                ->firstOrCreate([
                    'tenant_id' => $tenant->id,
                    'user_id'   => $userData->id,
                    'name'      => $userData['username'],
                    'status'    => true
                ]);

            if ($userProfile->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $existingCount++;
            }
        }

        $this->command?->info(sprintf('Successfully seeded %d user profile module for %s tenant. %d created, %d already existed.', count($users), $tenant->slug, $createdCount, $existingCount));
    }
}
