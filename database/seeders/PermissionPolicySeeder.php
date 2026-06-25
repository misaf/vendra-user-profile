<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Database\Seeders;

use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;
use Misaf\VendraTenant\Concerns\RequiresCurrentTenant;
use Misaf\VendraUserProfile\Enums\UserProfilePolicyEnum;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    use RequiresCurrentTenant;

    protected const string MODULE_NAME = 'vendra-user-profile';

    public function run(): void
    {
        $tenant = $this->currentTenant();

        $this->seedPermissionPolicies($tenant->getKey());
    }

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return array_column(UserProfilePolicyEnum::cases(), 'value');
    }

}
