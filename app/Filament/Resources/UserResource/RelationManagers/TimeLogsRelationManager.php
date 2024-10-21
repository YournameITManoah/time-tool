<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Components\TimeLogForm;
use App\Filament\Resources\TimeLogResource;
use App\Rules\Timeframe;
use App\TimeLogFormType;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'timeLogs';

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                TimeLogForm::schema(TimeLogFormType::USER)
            );
    }

    public function table(Table $table): Table
    {
        return TimeLogResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
