<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MyProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
    protected static ?string $title = 'My Projects';
    protected static ?string $breadcrumb = 'My';

    public function table(Table $table): Table
    {
        return ProjectResource::table($table)
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('users', function (Builder $query) {
                $query->where('user_id', auth()->id());
            }))
            ->actions([])
            ->bulkActions([]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->check();
    }
}
