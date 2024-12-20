<?php

namespace App\Filament\Resources;

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

class UserTaskResource extends Resource
{
    protected static ?string $model = UserTask::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->translateLabel()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->translateLabel()
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('task_id')
                    ->translateLabel()
                    ->relationship('task', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->translateLabel()
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.name')
                    ->translateLabel()
                    ->numeric()
                    ->sortable()
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('user')
                    ->translateLabel()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('project')
                    ->translateLabel()
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('task')
                    ->translateLabel()
                    ->relationship('task', 'name')
                    ->searchable()
                    ->preload()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserTasks::route('/'),
            'create' => Pages\CreateUserTask::route('/create'),
            'edit' => Pages\EditUserTask::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('User Task');
    }

    public static function getPluralModelLabel(): string
    {
        return __('User Tasks');
    }
}
