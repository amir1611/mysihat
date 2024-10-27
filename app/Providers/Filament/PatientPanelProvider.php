<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ChatBot;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class PatientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('patient')
            ->path('patient/')
            ->login()
            ->colors([
                'danger' => Color::rgb('rgb(255,0,0)'),
                'gray' => Color::Gray,
                'info' => Color::Green,
                'primary' => Color::Violet, // ,'#2720ff'
                'success' => Color::Lime,
                'warning' => Color::rgb('rgb(255,69,0)'),
                'edit' => Color::Blue,
                'warning' => Color::Amber,
            ])
            ->brandLogo(asset('build/assets/mysihat_logo.png'))
            ->brandLogoHeight('4rem')
            ->viteTheme('resources/css/filament/patient/theme.css')
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                // ChatBot::class,
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
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Settings Profile')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    //     ->canAccess(fn () => auth()->user()->id === 1)
                    ->shouldRegisterNavigation(true)
                    ->shouldShowDeleteAccountForm(true)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),

                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->timezone('Asia/Kuala_Lumpur')
            ]);
    }
}
