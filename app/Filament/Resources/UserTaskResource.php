<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\RelationManagers\UserTasksRelationManager as TaskUserTasksRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\UserTasksRelationManager as UserUserTasksRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\UserTasksRelationManager as ProjectUserTasksRelationManager;
use App\Filament\Resources\UserTaskResource\Pages;
use App\Filament\Resources\UserTaskResource\RelationManagers;
use App\Models\UserTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * A task belonging to a specific user within a specific project
 */
class UserTaskResource extends Resource
{
    // The related model
    protected static ?string $model = UserTask::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // The navigation order of the resource
    protected static ?int $navigationSort = 1;

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
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->hiddenOn(UserUserTasksRelationManager::class),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->hiddenOn(ProjectUserTasksRelationManager::class),
                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'name')
                    ->hiddenOn(TaskUserTasksRelationManager::class),
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
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->searchable()
                    ->hiddenOn(UserUserTasksRelationManager::class),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->searchable()
                    ->hiddenOn(ProjectUserTasksRelationManager::class),
                Tables\Columns\TextColumn::make('task.name')
                    ->numeric()
                    ->searchable()
                    ->hiddenOn(TaskUserTasksRelationManager::class),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('task')
                    ->relationship('task', 'name')
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
            //
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserTasks::route('/'),
            'create' => Pages\CreateUserTask::route('/create'),
            'edit' => Pages\EditUserTask::route('/{record}/edit'),
        ];
    }

    /**
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('User Task');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('User Tasks');
    }
}
