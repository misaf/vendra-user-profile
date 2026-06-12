<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\VendraActivityLog\Concerns\HasDefaultActivityLogOptions;
use Misaf\VendraTenant\Traits\BelongsToTenant;
use Misaf\VendraUser\Traits\BelongsToUser;
use Misaf\VendraUserProfile\Database\Factories\UserProfileFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property bool $is_default
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'description', 'slug', 'is_default', 'status'])]
#[Hidden(['tenant_id'])]
final class UserProfile extends Model
{
    use BelongsToTenant;
    use BelongsToUser;
    use HasDefaultActivityLogOptions;

    /** @use HasFactory<UserProfileFactory> */
    use HasFactory;

    use HasSlug;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'          => 'integer',
            'tenant_id'   => 'integer',
            'name'        => 'string',
            'description' => 'string',
            'slug'        => 'string',
            'is_default'  => 'boolean',
            'status'      => 'boolean',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return "{$this->first_name} {$this->last_name}";
            },
        );
    }

    /**
     * @return MorphMany<Media, $this>
     */
    public function multimedia(): MorphMany
    {
        return $this->media();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
