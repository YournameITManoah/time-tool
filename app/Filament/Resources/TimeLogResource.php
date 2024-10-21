<?php

namespace App\Filament\Resources;

use App\Filament\Components\TimeLogForm;
use App\Filament\Resources\TimeLogResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\TimeLogsRelationManager as ProjectTimeLogsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\TimeLogsRelationManager as UserTimeLogsRelationManager;
use App\Filament\Resources\TimeLogResource\Pages\MyTimeLogs;
use App\Models\Project;
use App\Models\TimeLog;
use App\Rules\Timeframe;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class TimeLogResource extends Resource
{
    protected static ?string $model = TimeLog::class;

    protected static ?int $navigationSort = 0;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                TimeLogForm::schema()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->hiddenOn([
                        UserTimeLogsRelationManager::class,
                        MyTimeLogs::class,
                    ]),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable()
                    ->hiddenOn(ProjectTimeLogsRelationManager::class),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('h:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stop_time')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListTimeLogs::route('/'),
            'create' => Pages\CreateTimeLog::route('/create'),
            'edit' => Pages\EditTimeLog::route('/{record}/edit'),
            'my' => Pages\MyTimeLogs::route('/my')
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }
}
