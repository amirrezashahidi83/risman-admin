<?php

namespace App\Providers\Filament;

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
use App\Livewire\StatsOverview;
use App\Filament\Resources\UserResource\Widgets\LatestRegistredUsers;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\CounselorResource;
use App\Filament\Resources\StudyPlanResource\Widgets\LastReports;
use App\Filament\Resources\CounselorPlanResource\Widgets\LastPlans;
use app\Filament\Admin\Pages\Login;
use App\Models\School;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use App\Filament\Admin\Pages\Dashboard;
use App\Http\Middleware\ChangeTheme;
class AdminPanelProvider extends PanelProvider
{
    

    public function boot(){
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('/')
            ->login(Login::class)
            ->brandLogo(fn () => view('logo'))
            ->brandName('پنل ادمین')
            ->colors([
                'primary' => "#008dcb",
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                StatsOverview::class,
                //LatestRegistredUsers::class,
                LastReports::class,
                LastPlans::class
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
                ChangeTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
        ->spa()
        ->navigationGroups([
            NavigationGroup::make('')
                ->label('کاربران')
                ->icon('heroicon-o-user'),
            NavigationGroup::make()
                ->label('عملکرد')
                ->icon('heroicon-o-list-bullet'),
            NavigationGroup::make()
                ->label('تنظیمات')
                ->icon('heroicon-o-cog-6-tooth')
                ->collapsed(),
        ])
        ->plugin(FilamentTourPlugin::make());
        FilamentTourPlugin::make()->enableCssSelector();
    }
}
