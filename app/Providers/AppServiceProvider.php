<?php

namespace App\Providers;

use App\Models\User;
use Filament\Tables\Table;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Console\CliDumper;
use Illuminate\Foundation\Http\HtmlDumper;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        HtmlDumper::dontIncludeSource();
        CliDumper::dontIncludeSource();
        Validator::excludeUnvalidatedArrayKeys();
        Model::shouldBeStrict();
        Model::unguard();
        Relation::enforceMorphMap([
            'user' => User::class,
        ]);
        Date::use(CarbonImmutable::class);

        Password::defaults(function () {
            $rule = Password::min(8)->letters()->mixedCase()->numbers()->symbols();

            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });

        Table::configureUsing(function (Table $table): void {
            $table->deferLoading()->extremePaginationLinks();
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'nl'])
                ->labels([
                    'en' => 'English',
                    'nl' => 'Nederlands',
                ])
                ->flags([
                    'en' => asset('img/flags/en.svg'),
                    'nl' => asset('img/flags/nl.svg'),
                ]);
        });
    }
}
