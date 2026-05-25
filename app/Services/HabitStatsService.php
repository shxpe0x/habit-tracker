<?php

namespace App\Services;

use App\Models\HabitLog;
use App\Models\User;
use Carbon\CarbonPeriod;

class HabitStatsService
{
    public function dailyCompletion(User $user, int $days = 7): array
    {
        $habits = $user->habits()->active()->get();

        if ($habits->isEmpty()) {
            return ['labels' => [], 'data' => []];
        }

        $start = now()->subDays($days - 1)->startOfDay();
        $habitIds = $habits->pluck('id')->all();

        // Один запрос: уникальные пары (habit_id, completed_on) за период
        $completed = HabitLog::query()
            ->whereIn('habit_id', $habitIds)
            ->whereDate('completed_on', '>=', $start->toDateString())
            ->get(['habit_id', 'completed_on'])
            ->groupBy(fn ($log) => $log->completed_on->toDateString())
            ->map(fn ($logs) => $logs->pluck('habit_id')->unique()->count());

        $labels = [];
        $percentages = [];
        $totalHabits = $habits->count();

        foreach (CarbonPeriod::create($start, now()) as $date) {
            $dateStr = $date->toDateString();
            $labels[] = $date->format('d.m');
            $count = $completed->get($dateStr, 0);
            $percentages[] = round(($count / $totalHabits) * 100, 1);
        }

        return ['labels' => $labels, 'data' => $percentages];
    }

    public function perHabitBreakdown(User $user, int $days = 7): array
    {
        $habits = $user->habits()->active()->get();

        if ($habits->isEmpty()) {
            return ['labels' => [], 'data' => [], 'colors' => []];
        }

        $start = now()->subDays($days - 1)->toDateString();

        // Один запрос: количество логов на привычку
        $countsByHabit = HabitLog::query()
            ->whereIn('habit_id', $habits->pluck('id'))
            ->whereDate('completed_on', '>=', $start)
            ->selectRaw('habit_id, COUNT(*) as cnt')
            ->groupBy('habit_id')
            ->pluck('cnt', 'habit_id');

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($habits as $habit) {
            $labels[] = $habit->title;
            $data[] = (int) ($countsByHabit[$habit->id] ?? 0);
            $colors[] = $habit->color;
        }

        return ['labels' => $labels, 'data' => $data, 'colors' => $colors];
    }

    public function summary(User $user): array
    {
        $activeHabits = $user->habits()->active()->count();
        $completedToday = 0;

        if ($activeHabits > 0) {
            $completedToday = $user->habits()
                ->active()
                ->whereHas('logs', fn ($q) => $q->whereDate('completed_on', today()))
                ->count();
        }

        $weekData = $this->dailyCompletion($user, 7);
        $weekAvg = count($weekData['data']) > 0
            ? round(array_sum($weekData['data']) / count($weekData['data']), 1)
            : 0;

        return [
            'active_habits' => $activeHabits,
            'completed_today' => $completedToday,
            'week_avg_percent' => $weekAvg,
        ];
    }

    /**
     * Heatmap последних N недель: [['date' => '2026-05-25', 'count' => 3, 'level' => 0..4], ...]
     * Возвращаем по неделям (7 дней в каждой), последняя — текущая.
     *
     * @return array{weeks: array<int, array<int, ?array{date: string, count: int, level: int}>>, max: int}
     */
    public function heatmap(User $user, int $weeks = 12): array
    {
        $habitIds = $user->habits()->pluck('id');

        if ($habitIds->isEmpty()) {
            return ['weeks' => [], 'max' => 0];
        }

        $end = now()->endOfWeek(\Carbon\CarbonInterface::SUNDAY);
        $start = $end->copy()->subWeeks($weeks - 1)->startOfWeek(\Carbon\CarbonInterface::MONDAY);

        // Один запрос: даты + количество отметок (по уникальным парам habit+date)
        $byDate = HabitLog::query()
            ->whereIn('habit_id', $habitIds)
            ->whereDate('completed_on', '>=', $start->toDateString())
            ->whereDate('completed_on', '<=', $end->toDateString())
            ->selectRaw('completed_on, COUNT(*) as cnt')
            ->groupBy('completed_on')
            ->pluck('cnt', 'completed_on')
            ->mapWithKeys(fn ($cnt, $date) => [\Carbon\Carbon::parse($date)->toDateString() => (int) $cnt]);

        $max = $byDate->max() ?: 1;

        $result = [];
        $cursor = $start->copy();
        for ($w = 0; $w < $weeks; $w++) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $cursor->toDateString();
                if ($cursor->isFuture()) {
                    $week[] = null;
                } else {
                    $count = $byDate->get($dateStr, 0);
                    $level = match (true) {
                        $count === 0 => 0,
                        $count <= max(1, (int) round($max * 0.25)) => 1,
                        $count <= max(2, (int) round($max * 0.5)) => 2,
                        $count <= max(3, (int) round($max * 0.75)) => 3,
                        default => 4,
                    };
                    $week[] = ['date' => $dateStr, 'count' => $count, 'level' => $level];
                }
                $cursor->addDay();
            }
            $result[] = $week;
        }

        return ['weeks' => $result, 'max' => $max];
    }
}
