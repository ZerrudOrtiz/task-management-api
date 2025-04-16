<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskWidgetChart extends BaseWidget
{
protected function getStats(): array
{
    $counts = Task::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

    $total = $counts->sum();

    return [
        Stat::make('pending', $counts[config('constants.TASK_STATUS_PENDING')] ?? 0),
        Stat::make('in_progress', $counts[config('constants.TASK_STATUS_IN_PROGRESS')] ?? 0)->label('In Progress'),
        Stat::make('completed', $counts[config('constants.TASK_STATUS_COMPLETED')] ?? 0),
        Stat::make('total_tasks', $total)->label('Total Tasks'),
    ];
}
}
