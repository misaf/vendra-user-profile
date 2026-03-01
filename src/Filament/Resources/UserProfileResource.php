<?php

declare(strict_types=1);

namespace Misaf\VendraUserProfile\Filament\Resources;

use Filament\Actions\Action;
use Filament\Clusters\Cluster;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Laravel\Pennant\Feature;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Filament\Clusters\Resources\UserProfileDocuments\RelationManagers\UserProfileDocumentRelationManager;
use Misaf\VendraUser\Filament\Clusters\Resources\UserProfilePhones\RelationManagers\UserProfilePhoneRelationManager;
use Misaf\VendraUser\Filament\Clusters\UsersCluster;
use Misaf\VendraUserProfile\Enums\UserProfileFeatureEnum;
use Misaf\VendraUserProfile\Filament\Resources\Pages\CreateUserProfile;
use Misaf\VendraUserProfile\Filament\Resources\Pages\EditUserProfile;
use Misaf\VendraUserProfile\Filament\Resources\Pages\ListUserProfiles;
use Misaf\VendraUserProfile\Filament\Resources\Pages\ViewUserProfile;
use Misaf\VendraUserProfile\Filament\Resources\Schemas\UserProfileForm;
use Misaf\VendraUserProfile\Filament\Resources\Tables\UserProfileTable;
use Misaf\VendraUserProfile\Models\UserProfile;

final class UserProfileResource extends Resource
{
    protected static ?string $model = UserProfile::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'profiles';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = UsersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.user_profile');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.user_profile');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user', 'latestUserProfilePhone']);
    }

    /**
     * @return array<Action>
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(self::getUrl('view', ['record' => $record])),
        ];
    }

    /**
     * @return array<string, HtmlString>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('form.email') => str('<span dir="ltr">' . $record->user->email . '</span>')->toHtmlString(),
            __('form.phone') => str('<span dir="ltr">' . $record?->latestUserProfilePhone?->phone . '</span>')->toHtmlString(),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return str('<span dir="ltr">' . $record->full_name . '</span>')->toHtmlString();
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListUserProfiles::route('/'),
            'create' => CreateUserProfile::route('/create'),
            'view'   => ViewUserProfile::route('/{record}'),
            'edit'   => EditUserProfile::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<class-string<RelationManager>|RelationGroup|RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            // UserProfileDocumentRelationManager::class,
            // UserProfilePhoneRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return UserProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserProfileTable::configure($table);
    }

    public static function canAccess(): bool
    {
        $tenant = Tenant::current();

        return Feature::for($tenant)->active(UserProfileFeatureEnum::MODULE_ENABLED->value);
    }
}
