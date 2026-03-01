<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Misaf\VendraUser\Models\UserProfile;

trait BelongsToUserProfile
{
    /**
     * @return BelongsTo<UserProfile, $this>
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }
}
