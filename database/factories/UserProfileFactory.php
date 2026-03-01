<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Models\UserProfile;

/**
 * @extends Factory<UserProfile>
 */
final class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'tenant_id'   => Tenant::factory(),
            'user_id'     => User::factory(),
            'name'        => fake()->sentences(1, true),
            'description' => fake()->realTextBetween(100, 200),
            'slug'        => fn(array $attributes) => Str::slug($attributes['name']),
            'is_default'  => fake()->boolean(1),
            'status'      => fake()->boolean(80),
        ];
    }

    public function forTenant(Tenant|int $tenant): static
    {
        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;

        return $this->state(fn(): array => ['tenant_id' => $tenantId]);
    }

    public function forUser(User|int $user): static
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->state(fn(): array => ['user_id' => $userId]);
    }

    public function enabled(): static
    {
        return $this->state(fn(): array => ['status' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(): array => ['status' => false]);
    }
}
