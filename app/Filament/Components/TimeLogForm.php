<?php

namespace App\Filament\Components;

use Closure;
use App\Models\Project;
use App\TimeLogFormType;
use Filament\Forms;
use App\Rules\Timeframe;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TimeLogForm
{
    public static function schema(TimeLogFormType $type = TimeLogFormType::DEFAULT): array
    {
        $default = [
            Forms\Components\DatePicker::make('date')
                ->required()
                ->maxDate(now()),
            Forms\Components\TimePicker::make('start_time')
                ->required()
                ->seconds(false),
            Forms\Components\TimePicker::make('stop_time')
                ->required()
                ->seconds(false)
                ->after('start_time'),
            Forms\Components\Textarea::make('description')
                ->required()
                ->columnSpanFull(),
        ];

        if ($type === TimeLogFormType::PROJECT) {
            return array_merge([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(function (RelationManager $livewire): array {
                        return $livewire->getOwnerRecord()->users()->pluck('name', 'id')->toArray();
                    })
                    ->required(),
                Forms\Components\TextInput::make('project_id')
                    ->label('Project')
                    ->disabled()
            ], $default);
        } elseif ($type === TimeLogFormType::USER) {
            return array_merge([
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->options(function (RelationManager $livewire): array {
                        return $livewire->getOwnerRecord()->projects()->pluck('name', 'id')->toArray();
                    })
                    ->required()
            ], $default);
        } else {
            return array_merge([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->live()
                    ->required()
                    ->disabled(! auth()->user()->isAdmin())
                    ->dehydrated(! auth()->user()->isAdmin())
                    ->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail) {
                            if (auth()->user()->isAdmin()) return;
                            if ($value !== auth()->id()) {
                                $fail('The :attribute is invalid.');
                            }
                        },
                    ]),
                Forms\Components\Select::make('project_id')
                    ->options(fn(Forms\Get $get): Collection => Project::query()
                        ->whereHas('users', function (Builder $query) use ($get) {
                            $query->where('user_id', $get('user_id'));
                        })
                        ->pluck('name', 'id'))
                    ->required()
            ], $default);
        }
    }
}
