<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StatisticsExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $user = auth()->user();
        $filename = 'habit-tracker-export-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($user) {
            $handle = fopen('php://output', 'w');
            // BOM для корректного отображения кириллицы в Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Дата', 'Привычка', 'Цвет', 'Время дня']);

            // lazy() стримит безопасно через cursor по id, без потерь между чанками
            HabitLog::query()
                ->whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
                ->with('habit:id,title,color,time_of_day')
                ->orderBy('id')
                ->lazy(500)
                ->each(function (HabitLog $log) use ($handle) {
                    fputcsv($handle, [
                        $log->completed_on->format('Y-m-d'),
                        $log->habit->title,
                        $log->habit->color,
                        Habit::TIME_LABELS[$log->habit->time_of_day ?? 'any'] ?? '',
                    ]);
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
