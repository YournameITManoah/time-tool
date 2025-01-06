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

/**
 * A single time registration entry
 */
class TimeLogResource extends Resource
{
    // The related model
    protected static ?string $model = TimeLog::class;

    // The icon to use in the navigation menu
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    // The navigation order of the resource
    protected static ?int $navigationSort = 0;

    // The navigation group of the resource
    protected static ?string $navigationGroup = 'Admin';

    /**
     * The fields required to create/update the resource
     * @param \Filament\Forms\Form $form The base form
     * @return \Filament\Forms\Form The defined form
     */
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

    /**
     * The columns, filters and actions of the table that displays the resource
     * @param \Filament\Tables\Table $table The base table
     * @return \Filament\Tables\Table The defined table
     */
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

    /**
     * Relationships to be displayed on the detail page of the resource
     * @return array The relations
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * The pages of the resource
     * @return array The pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeLogs::route('/'),
            'create' => Pages\CreateTimeLog::route('/create'),
            'edit' => Pages\EditTimeLog::route('/{record}/edit'),
        ];
    }

    /**
     * Whether to show the resource in the navigation
     * @return bool Whether to show
     */
    public static function shouldRegisterNavigation(): bool
    {
        return \Auth::user()->isAdmin();
    }

    /**
     * The label of the resource
     * @return string The label
     */
    public static function getModelLabel(): string
    {
        return __('Time Log');
    }

    /**
     * The plural form of the resource label
     * @return string The label
     */
    public static function getPluralModelLabel(): string
    {
        return __('Time Logs');
    }
}
