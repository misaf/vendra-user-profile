<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Enums\UserProfilePolicyEnum;
use Misaf\VendraUserProfile\Models\UserProfile;

final class UserProfilePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can(UserProfilePolicyEnum::CREATE);
    }

    public function delete(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::DELETE);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(UserProfilePolicyEnum::DELETE_ANY);
    }

    public function forceDelete(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::FORCE_DELETE);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can(UserProfilePolicyEnum::FORCE_DELETE_ANY);
    }

    public function replicate(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::REPLICATE);
    }

    public function restore(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::RESTORE);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can(UserProfilePolicyEnum::RESTORE_ANY);
    }

    public function update(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::UPDATE);
    }

    public function view(User $user, UserProfile $userProfile): bool
    {
        return $user->can(UserProfilePolicyEnum::VIEW);
    }

    public function viewAny(User $user): bool
    {
        return $user->can(UserProfilePolicyEnum::VIEW_ANY);
    }
}
