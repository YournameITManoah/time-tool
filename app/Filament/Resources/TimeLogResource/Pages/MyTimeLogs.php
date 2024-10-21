<?php

namespace App\Filament\Resources\TimeLogResource\Pages;

use App\Filament\Exports\TimeLogExporter;
use App\Filament\Resources\TimeLogResource;
use App\Models\TimeLog;
use App\Filament\Components\TimeLogForm;
use App\TimeLogFormType;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;

class MyTimeLogs extends ManageRecords
{
    protected static string $resource = TimeLogResource::class;
    protected static ?string $title = 'My Time Logs';
    protected static ?string $breadcrumb = 'My';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }

    public function table(Table $table): Table
    {
        return TimeLogResource::table($table)
            ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('user', function (Builder $query) {
                $query->where('user_id', auth()->id());
            }))
            ->headerActions([
                ExportAction::make()
                    ->exporter(TimeLogExporter::class)
            ]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->check();
    }
}
