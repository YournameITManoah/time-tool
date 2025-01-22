<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\ProjectsRelationManager;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * A client which users can log time for
 */
class ClientResource extends Resource
{
    // The related model
    protected static ?string $model = Client::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    // The navigation order of the resource
    protected static ?int $navigationSort = 40;

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
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email'),
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
            ->defaultSort('name', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
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
            ProjectsRelationManager::class
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
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
        return __('Client');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Clients');
    }
}
