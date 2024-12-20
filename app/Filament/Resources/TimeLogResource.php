<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeLogResource\Pages;
use App\Models\TimeLog;
use App\Rules\UniqueTimeLogFrame;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Exports\TimeLogExporter;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->translateLabel()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->translateLabel()
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('task_id')
                    ->translateLabel()
                    ->relationship('task', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->translateLabel()
                    ->required()
                    ->minDate('1 year ago')
                    ->maxDate('today'),
                Forms\Components\TimePicker::make('start_time')
                    ->translateLabel()
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('stop_time')
                    ->translateLabel()
                    ->seconds(false)
                    ->required()
                    ->after('start_time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.name')
                    ->translateLabel()
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->translateLabel()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->translateLabel()
                    ->time('h:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stop_time')
                    ->translateLabel()
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->translateLabel()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('project')
                    ->translateLabel()
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('task')
                    ->translateLabel()
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
        return \Auth::user()->isAdmin();
    }

    public static function getModelLabel(): string
    {
        return __('Time Log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Time Logs');
    }
}
