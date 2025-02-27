<?php

namespace App\Providers\Filament;

use App\Enums\MediaCollectionType;
use App\Enums\NavGroup;
use App\Filament\Pages\Auth\Register;
use App\Livewire\PersonalInfo;
use App\Models\User;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark-mode.png'))
            ->favicon(asset('images/logo-dark-mode.png'))
            ->domain(config('app.url'))
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(Register::class)
            ->profile()
            ->colors([
                'primary' => Color::Cyan
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin::make(),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: null, // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'profile' // Sets the slug for the profile page (default = 'my-profile')
                    )
                    ->myProfileComponents([
                        'personal_info' => PersonalInfo::class,
                        // UserProfileComponent::class,
                    ])
                    ->enableTwoFactorAuthentication(
                        force: true, // force the user to enable 2FA before they can use the application (default = false)
                    )
                    ->avatarUploadComponent(function (FileUpload $fileUpload) {
                        return $fileUpload
                            ->hiddenLabel()
                            ->afterStateHydrated(function (BaseFileUpload $component) {
                                $user = User::find(Auth::id());
                                $file = $user->getFirstMedia(MediaCollectionType::USER_PROFILE->value);
                                if (!$file) {
                                    $component->state([]);
                                    return;
                                }
                                $component->state([((string) Str::uuid()) => $file->getKey() . '/' . $file->file_name]);
                            })
                            ->deleteUploadedFileUsing(function () {
                                $user = User::find(Auth::id());
                                $user->clearMediaCollection(MediaCollectionType::USER_PROFILE->value);
                            })
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $user = User::find(Auth::id());
                                $user->clearMediaCollection(MediaCollectionType::USER_PROFILE->value);
                                $user->addMedia($file->path())->toMediaCollection(MediaCollectionType::USER_PROFILE->value);
                                $file = $user->getFirstMedia(MediaCollectionType::USER_PROFILE->value);
                                if (!$file) return null;
                                return $file->getKey() . '/' . $file->file_name;
                            });
                    }),
                \pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin::make(),
                \Rmsramos\Activitylog\ActivitylogPlugin::make()
                    ->navigationCountBadge(),
                \ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin::make(),
            ])
            ->navigationGroups([
                NavigationGroup::make(NavGroup::UM->value)
                    ->icon(NavGroup::UM->getIcon()),
                NavigationGroup::make(NavGroup::ST->value)
                    ->icon(NavGroup::ST->getIcon()),
            ])
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarCollapsibleOnDesktop()
            ->spa();
    }
}
