<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use \Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function form(Form $form): Form
    {
        return ProjectResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ProjectResource::table($table)
            ->headerActions([
                Tables\Actions\AttachAction::make()->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Projects');
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
