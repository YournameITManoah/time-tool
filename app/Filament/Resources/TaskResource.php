<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * A task which users can log time for
 */
class TaskResource extends Resource
{
    // The related model
    protected static ?string $model = Task::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    // The navigation order of the resource
    protected static ?int $navigationSort = 2;

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
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(100),
                Forms\Components\Toggle::make('billable')
                    ->translateLabel()
                    ->required(),
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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('billable')
                    ->translateLabel()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('billable')
                    ->translateLabel()
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
            //ProjectsRelationManager::class,
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
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
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('Task');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Tasks');
    }
}
