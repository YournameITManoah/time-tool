<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use Illuminate\Validation\Rules;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * A user
 */
class UserResource extends Resource
{
    // The related model
    protected static ?string $model = User::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // The navigation order of the resource
    protected static ?int $navigationSort = 50;

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
                    ->maxLength(length: 50)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('password')
                    ->rules([Rules\Password::defaults()]),
                Forms\Components\TextInput::make('available_hours')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(40),
                Forms\Components\TextInput::make('planned_hours')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(40)
                    ->lte('available_hours')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('available_hours')
                    ->numeric(),
                Tables\Columns\TextColumn::make('planned_hours')
                    ->numeric(),
                Tables\Columns\TextColumn::make('role.name'),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            RelationManagers\ConnectionsRelationManager::class,
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('User');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }
}
