<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers\ProjectsRelationManager as ClientProjectsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * A project which users can log time for
 */
class ProjectResource extends Resource
{
    // The related model
    protected static ?string $model = Project::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // The navigation order of the resource
    protected static ?int $navigationSort = 3;

    // The navigation group of the resource
    protected static ?string $navigationGroup = 'Admin';

    /**
     * The fields required to create/update the resource
     * @param \Filament\Forms\Form $form The base form
     * @return \Filament\Forms\Form The defined form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->hiddenOn(ClientProjectsRelationManager::class),
                Forms\Components\TextInput::make('name')
                    ->maxLength(50),
                Forms\Components\DatePicker::make('start_date')
                    ->required(false)
                    ->minDate('today')
                    ->maxDate('+10 years'),
                Forms\Components\DatePicker::make('end_date')
                    ->required(false)
                    ->minDate('today')
                    ->maxDate('+10 years')
                    ->afterOrEqual('start_date'),
                Forms\Components\TextInput::make('available_hours')
                    ->required(false)
                    ->numeric()
                    ->minValue(0)
                    ->default(null),
            ]);
    }

    /**
     * The columns, filters and actions of the table that displays the resource
     * @param \Filament\Tables\Table $table The base table
     * @return \Filament\Tables\Table The defined table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('start_date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->searchable()
                    ->hiddenOn(ClientProjectsRelationManager::class),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('available_hours')
                    ->numeric(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->sortable(false)
                    ->state(function (Project $record): float {
                        return ($record->start_date === null || $record->start_date->isBefore('now')) && ($record->end_date === null || $record->end_date->isAfter('now'));
                    }),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->default()
                    ->queries(
                        true: fn(Builder $query) => $query->where(function ($query) {
                            $query->whereNull('start_date')->orWhereDate('start_date', '<=', now());
                        })
                            ->where(function ($query) {
                                $query->whereNull('end_date')->orWhereDate('end_date', '>=', now());
                            }),
                        false: fn(Builder $query) => $query->where(function ($query) {
                            $query->whereNotNull('start_date')->whereDate('start_date', '>', now());
                        })
                            ->orWhere(function ($query) {
                                $query->whereNotNull('end_date')->whereDate('end_date', '<', now());
                            }),
                        blank: fn(Builder $query) => $query,
                    ),
                Tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->hiddenOn(ClientProjectsRelationManager::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Relationships to be displayed on the detail page of the resource
     * @return array The relations
     */
    public static function getRelations(): array
    {
        return [
                //TasksRelationManager::class,
            RelationManagers\UserTasksRelationManager::class,
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    /**
     * The navigation badge of the resource
     * @return mixed The value of the badge
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Whether to show the resource in the navigation
     * @return bool Whether to show
     */
    public static function shouldRegisterNavigation(): bool
    {
        return \Auth::user()->isAdmin();
    }

    /**
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('Project');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Projects');
    }
}
