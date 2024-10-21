<?php

namespace App\Filament\Resources\TimeLogResource\Widgets;

use App\Models\TimeLog;
use Illuminate\Support\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TimeLogsChart extends ChartWidget
{
    protected static ?string $heading = 'Worked hours';

    protected function getData(): array
    {
        // TODO": ask chatgpt to fix this
        $data = Trend::query(TimeLog::query()->selectRaw('TIMESTAMPDIFF(second, start_time, stop_time) AS `duration`'))
            ->dateColumn('start_time')
            ->between(
                start: Carbon::now()->startOfMonth(),
                end: Carbon::now()->endOfMonth(),
            )
            ->perDay()
            ->sum('duration');


        return
            [
                'datasets' => [
                    [
                        'label' => 'Hours worked',
                        'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    ],
                ],
                'labels' => $data->map(fn(TrendValue $value) => $value->date),
            ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
