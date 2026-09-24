<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Observers;

use Misaf\VendraSupport\Observers\Concerns\MaintainsSingleFlagPerOwner;

/**
 * Keep exactly one default profile per user. Synchronous, because the flag is
 * adjusted before the write.
 */
final class UserProfileObserver
{
    use MaintainsSingleFlagPerOwner;

    protected function flagColumn(): string
    {
        return 'is_default';
    }

    protected function ownerColumn(): string
    {
        return 'user_id';
    }
}
