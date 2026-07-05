<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Misaf\VendraUser\Models\User;
use Misaf\VendraUserProfile\Models\UserProfile;
use Misaf\VendraUserProfile\Tests\TestCase;
use Misaf\VendraUserProfile\Traits\BelongsToUserProfile;
use Misaf\VendraUserProfile\Traits\HasUserProfile;

uses(TestCase::class);

it('defines profile relationships for multiple profiles per user', function (): void {
    $user = new class () extends Model {
        use HasUserProfile;
    };

    expect($user->profiles())
        ->toBeInstanceOf(HasMany::class)
        ->and($user->profiles()->getRelated())
        ->toBeInstanceOf(UserProfile::class)
        ->and($user->userProfiles())
        ->toBeInstanceOf(HasMany::class)
        ->and((new UserProfile())->user())
        ->toBeInstanceOf(BelongsTo::class)
        ->and((new UserProfile())->user()->getRelated())
        ->toBeInstanceOf(User::class);
});

it('registers profiles as a dynamic relation on the user model', function (): void {
    expect((new User())->profiles())
        ->toBeInstanceOf(HasMany::class)
        ->and((new User())->profiles()->getRelated())
        ->toBeInstanceOf(UserProfile::class)
        ->and((new User())->userProfiles())
        ->toBeInstanceOf(HasMany::class);
});

it('defines the expected profile model attributes', function (): void {
    $profile = new UserProfile();

    expect($profile->getFillable())
        ->toBe(['tenant_id', 'user_id', 'name', 'description', 'slug', 'is_default', 'status'])
        ->and($profile->getHidden())
        ->toBe(['tenant_id'])
        ->and($profile->getCasts())
        ->toMatchArray([
            'id'        => 'integer',
            'tenant_id' => 'integer',
            'user_id'   => 'integer',
            'name'      => 'string',
        ]);
});

it('defines the inverse user profile trait relationship with the profile package model', function (): void {
    $model = new class () extends Model {
        use BelongsToUserProfile;
    };

    expect($model->userProfile())
        ->toBeInstanceOf(BelongsTo::class)
        ->and($model->userProfile()->getRelated())
        ->toBeInstanceOf(UserProfile::class);
});

it('uses name as the profile full name value', function (): void {
    $profile = new UserProfile([
        'name' => 'Primary Profile',
    ]);

    expect($profile->full_name)->toBe('Primary Profile');
});
