<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeLogResource\Pages;
use App\Models\TimeLog;
use App\Rules\UniqueTimeLogFrame;
use Carbon\Carbon;
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
    protected static ?int $navigationSort = 10;

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
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name'),
                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'name'),
                Forms\Components\DatePicker::make('date')
                    ->minDate('1 year ago')
                    ->maxDate('today'),
                Forms\Components\TimePicker::make('start_time')
                    ->seconds(false),
                Forms\Components\TimePicker::make('stop_time')
                    ->seconds(false)
                    ->after('start_time'),
                Forms\Components\Textarea::make('comments')
                    ->required(false)
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
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->formatStateUsing(fn(string $state): string => Carbon::parse($state)->diffInDays() < 1 ? __('Today') : Carbon::parse($state)->diffForHumans(['options' => \Carbon\CarbonInterface::ONE_DAY_WORDS]))
                    ->dateTooltip(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time('h:i'),
                Tables\Columns\TextColumn::make('stop_time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('comments')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('task')
                    ->relationship('task', 'name')
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
