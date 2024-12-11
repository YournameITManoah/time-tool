<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeLogResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\TimeLogsRelationManager as ProjectTimeLogsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\TimeLogsRelationManager as UserTimeLogsRelationManager;
use App\Models\Project;
use App\Models\TimeLog;
use App\Rules\Timeframe;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use App\Filament\Exports\TimeLogExporter;
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
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->minDate('1 year ago')
                    ->maxDate('today'),
                Forms\Components\TimePicker::make('start_time')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('stop_time')
                    ->seconds(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('h:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stop_time')
                    ->time('H:i')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(TimeLogExporter::class)
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
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }
}
