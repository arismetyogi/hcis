<?php

namespace App\Providers\Filament;

use App\Livewire\MyPassword;
use App\Livewire\MyProfile;
use Carbon\Carbon;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Niladam\FilamentAutoLogout\AutoLogoutPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'primary' => Color::Purple,
            ])
            // ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->brandLogo(url('https://www.biofarma.co.id/media/image/originals/post/2023/07/06/kf.png'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('images/kf.png'))
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
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->url('/admin/my-profile')
                    ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()
                    ->label('Logout')
            ])
            ->plugins([
                BreezyCore::make() // Filament Breezy
                ->withoutMyProfileComponents(['personal_info, update_password'])
                ->myProfileComponents([
                    'personal_info' => MyProfile::class,
                    'update_password' => MyPassword::class,])
                ->myProfile(
                    shouldRegisterNavigation: true,
                    hasAvatars: true,
                )
                ->passwordUpdateRules(
                    rules: [Password::default()->mixedCase()->numbers()->uncompromised(3)]
                ),
                AutoLogoutPlugin::make()
                    ->color(Color::Amber)                   // Set the color. Defaults to Zinc
                    //->disableIf(fn () => auth()->id() === 1)        // Disable the user with ID 1
                    ->logoutAfter(Carbon::SECONDS_PER_MINUTE * 15)   // Logout the user after 5 minutes
                    ->timeLeftText('You\'ll be logged out in: ')      // Change the time left text
        ]);
    }
}
