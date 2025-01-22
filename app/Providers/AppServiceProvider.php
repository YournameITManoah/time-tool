<?php

namespace App\Providers;

use App\Models\User;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Console\CliDumper;
use Illuminate\Foundation\Http\HtmlDumper;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

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
                ? $rule->uncompromised()
                : $rule;
        });

        // Filament form default
        Field::configureUsing(function (Field $field) {
            $field->translateLabel()->required();
        });

        Checkbox::configureUsing(function (Checkbox $checkbox) {
            $checkbox->required(false);
        });

        TextInput::configureUsing(function (TextInput $text) {
            $text->maxLength(100);

            if ($text->getName() === 'email') {
                $text->email()->unique(ignoreRecord: true);
            }

            if ($text->getName() === 'password') {
                $text->password()->revealable();
            }
        });

        Select::configureUsing(function (Select $select) {
            $select->searchable()->preload();
        });

        Textarea::configureUsing(function (Textarea $textarea) {
            $textarea->autosize()->maxLength(255);
        });

        // Filament table defaults
        Table::configureUsing(function (Table $table): void {
            $table->deferLoading()->extremePaginationLinks();
        });

        Column::configureUsing(function (Column $column): void {
            $column->translateLabel()->sortable()->wrap();
        });

        TextColumn::configureUsing(function (TextColumn $column): void {
            if ($column->getName() === 'email') {
                $column->icon('heroicon-m-envelope')->copyable();
            }

            if ($column->isCopyable($column->getState())) {
                $column->tooltip(__('Click to copy'));
            }

            if (in_array($column->getName(), ['created_at', 'updated_at'])) {
                $column->since()->dateTimeTooltip()->toggleable(isToggledHiddenByDefault: true);
            }
        });

        Filter::configureUsing(function (Filter $filter): void {
            $filter->translateLabel();
        });

        SelectFilter::configureUsing(function (SelectFilter $filter): void {
            $filter->searchable()->preload();
        });

        TernaryFilter::configureUsing(function (SelectFilter $filter): void {
            $filter->searchable(false);
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

        // API docs defaults
        Scramble::ignoreDefaultRoutes();
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(SecurityScheme::http('bearer'));
        });
    }
}
