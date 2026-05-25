<?php

namespace App\Livewire;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Services\HabitStatsService;
use App\Services\MotivationService;
use App\Services\UserStreakService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use AuthorizesRequests;

    public function toggleToday(int $habitId): void
    {
        $habit = Habit::where('user_id', auth()->id())->findOrFail($habitId);
        $this->authorize('update', $habit);

        $log = $habit->logs()->whereDate('completed_on', today())->first();

        if ($log) {
            $log->delete();
        } else {
            HabitLog::create([
                'habit_id' => $habit->id,
                'completed_on' => today(),
            ]);
        }
    }

    public function render(
        HabitStatsService $stats,
        UserStreakService $streakService,
        MotivationService $motivation,
    ) {
        $user = auth()->user();

        // Один запрос на привычки
        $habits = $user->habits()
            ->active()
            ->orderBy('title')
            ->get();

        // Один запрос на сегодняшние отметки
        $completedIds = $habits->isEmpty()
            ? []
            : HabitLog::query()
                ->whereIn('habit_id', $habits->pluck('id'))
                ->whereDate('completed_on', today())
                ->pluck('habit_id')
                ->all();

        // Сортируем по времени дня
        $currentHour = now()->hour;
        $habits = $habits->sortBy(fn ($h) => [
            Habit::timeWeight($h->time_of_day ?? Habit::TIME_ANY, $currentHour),
            $h->title,
        ])->values();

        $todayDone = count($completedIds);
        $todayTotal = $habits->count();

        // Серия — один запрос на все даты
        $streak = $streakService->current($user);
        $longestStreak = $streakService->longest($user);

        // Опасность стрика вычисляем без доп. запроса — данные уже есть
        $streakInDanger = $streak > 0 && $todayDone === 0;

        // Среднее за 7 дней — один запрос внутри dailyCompletion
        $weekData = $stats->dailyCompletion($user, 7);
        $weekAvg = count($weekData['data']) > 0
            ? round(array_sum($weekData['data']) / count($weekData['data']), 1)
            : 0;

        return view('livewire.dashboard', [
            'habits' => $habits,
            'completedIds' => $completedIds,
            'summary' => [
                'active_habits' => $todayTotal,
                'completed_today' => $todayDone,
                'week_avg_percent' => $weekAvg,
            ],
            'streak' => $streak,
            'longestStreak' => $longestStreak,
            'streakInDanger' => $streakInDanger,
            'todayDone' => $todayDone,
            'todayTotal' => $todayTotal,
            'todayPercent' => $todayTotal > 0 ? round($todayDone / $todayTotal * 100) : 0,
            'motivation' => $motivation->forUser($streak, $todayDone, $todayTotal),
        ]);
    }
}
