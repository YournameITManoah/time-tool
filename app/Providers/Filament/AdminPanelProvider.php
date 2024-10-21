<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ProjectResource\Pages\MyProjects;
use App\Filament\Resources\TimeLogResource\Pages\MyTimeLogs;
use App\Filament\Resources\TimeLogResource\Widgets\TimeLogsChart;
use App\Models\Project;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\Navigation\NavigationItem;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //TimeLogsChart::class,
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
            ->navigationItems([
                NavigationItem::make()
                    ->label(fn(): string => MyProjects::getNavigationLabel())
                    ->icon('heroicon-o-rectangle-stack')
                    ->url(fn(): string => MyProjects::getUrl())
                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.projects.my'))
                    ->badge(fn() => Project::query()->whereHas('users', function (Builder $query) {
                        $query->where('user_id', auth()->id());
                    })->count()),
                NavigationItem::make()
                    ->label(fn(): string => MyTimeLogs::getNavigationLabel())
                    ->icon('heroicon-o-clock')
                    ->url(fn(): string => MyTimeLogs::getUrl())
                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.time-logs.my'))
            ]);
    }
}
