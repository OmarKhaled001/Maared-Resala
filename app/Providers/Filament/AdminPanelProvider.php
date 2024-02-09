<?php

namespace App\Providers\Filament;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\EventResource\Widgets\EventChart;
use App\Filament\Resources\VolunteerResource;
use App\Filament\Resources\VolunteerResource\Widgets\volunteerChart;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Awcodes\Overlook\OverlookPlugin;
use Filament\Http\Middleware\Authenticate;
use Awcodes\Overlook\Widgets\OverlookWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    protected ?string $title  = 'الرئسية';
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->registration()
            ->login()
            ->favicon(asset('images/logo.png'))
            ->brandName('معارض داخلي')
            ->colors([
                'primary' => Color::Purple,
            ])
            ->font('cairo')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')

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
            
            ->widgets([
                volunteerChart::class,
                EventChart::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
            
    }
}
