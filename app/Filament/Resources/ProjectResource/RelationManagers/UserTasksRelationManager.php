<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\UserTaskResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use \Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'userTasks';

    public function form(Form $form): Form
    {
        return UserTaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return UserTaskResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('User Tasks');
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
