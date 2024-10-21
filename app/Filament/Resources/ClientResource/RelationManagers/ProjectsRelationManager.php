<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
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
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
