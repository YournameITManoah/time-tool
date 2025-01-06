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

/**
 * Apply a mapping callback receiving key and value as arguments.
 * The standard array_map doesn't pass the key to the callback. But in the case of associative arrays,
 * it could be really helpful.
 *
 * array_map_assoc(function ($key, $value) {
 *  ...
 * }, $items)
 *
 * @param callable $callback
 * @param array $array
 * @return array
 */
function array_map_assoc(callable $callback, array $array): array
{
    $result = [];
    foreach ($array as $key => $val) {
        $result[$key] = $callback($key, $val);
    }
    return $result;
}

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

        // Password defaults
        Password::defaults(function () {
            $rule = Password::min(8)->letters()->mixedCase()->numbers()->symbols();

            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });

        // Filament table defaults
        Table::configureUsing(function (Table $table): void {
            $table->deferLoading()->extremePaginationLinks();
        });

        // Filament LanguageSwitch configuration
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(array_column(\App\SupportedLocale::cases(), 'value'))
                ->labels(\Config::get('app.locales'))
                ->flags(array_map_assoc(
                    fn($key) => asset("img/flags/$key.svg"),
                    \Config::get('app.locales')
                ));
        });
    }
}
