<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\ConnectionResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConnectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'connections';

    public function form(Form $form): Form
    {
        return ConnectionResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ConnectionResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Connections');
    }

    /**
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('Connection');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Connections');
    }
}
