<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\HabitLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ChallengeProgressService
{
    public function calculate(Challenge $challenge): array
    {
        $habits = $this->resolveHabits($challenge);
        $current = match ($challenge->type) {
            Challenge::TYPE_STREAK => $this->calculateStreak($habits, $challenge),
            Challenge::TYPE_TOTAL_COMPLETIONS => $this->calculateTotalCompletions($habits, $challenge),
            Challenge::TYPE_PERIOD_DAYS => $this->calculatePeriodDays($habits, $challenge),
            default => 0,
        };

        $target = max($challenge->target_value, 1);
        $percent = min(100, round(($current / $target) * 100, 1));

        return [
            'current' => $current,
            'target' => $challenge->target_value,
            'percent' => $percent,
        ];
    }

    protected function resolveHabits(Challenge $challenge): Collection
    {
        // Если habits загружены через eager loading — используем их без доп. запросов
        if ($challenge->relationLoaded('habits')) {
            $loaded = $challenge->habits;

            if ($loaded->isNotEmpty()) {
                // Из загруженных уже отфильтровали активные через with(['habits' => ...])
                return collect($loaded->where('is_active', true)->values());
            }

            // Пусто — челлендж распространяется на все активные привычки пользователя
            return $challenge->user->habits()->active()->get();
        }

        if ($challenge->appliesToAllHabits()) {
            return $challenge->user->habits()->active()->get();
        }

        return $challenge->habits()->active()->get();
    }

    protected function periodStart(Challenge $challenge): Carbon
    {
        return $challenge->starts_on->copy()->startOfDay();
    }

    protected function periodEnd(Challenge $challenge): Carbon
    {
        return ($challenge->ends_on ?? now())->copy()->endOfDay();
    }

    protected function calculateTotalCompletions(Collection $habits, Challenge $challenge): int
    {
        if ($habits->isEmpty()) {
            return 0;
        }

        return HabitLog::query()
            ->whereIn('habit_id', $habits->pluck('id'))
            ->whereDate('completed_on', '>=', $this->periodStart($challenge))
            ->whereDate('completed_on', '<=', $this->periodEnd($challenge))
            ->count();
    }

    protected function calculatePeriodDays(Collection $habits, Challenge $challenge): int
    {
        if ($habits->isEmpty()) {
            return 0;
        }

        return HabitLog::query()
            ->whereIn('habit_id', $habits->pluck('id'))
            ->whereDate('completed_on', '>=', $this->periodStart($challenge))
            ->whereDate('completed_on', '<=', $this->periodEnd($challenge))
            ->distinct()
            ->count('completed_on');
    }

    protected function calculateStreak(Collection $habits, Challenge $challenge): int
    {
        if ($habits->isEmpty()) {
            return 0;
        }

        $start = $this->periodStart($challenge);
        $challengeEnd = $this->periodEnd($challenge);
        $now = now()->endOfDay();
        $end = $challengeEnd->lte($now) ? $challengeEnd : $now;

        // Если конец раньше начала — серии нет
        if ($end->lt($start)) {
            return 0;
        }

        // Один запрос: все даты с отметками за период
        $completedDates = HabitLog::query()
            ->whereIn('habit_id', $habits->pluck('id'))
            ->whereDate('completed_on', '>=', $start->toDateString())
            ->whereDate('completed_on', '<=', $end->toDateString())
            ->pluck('completed_on')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->flip();

        $maxStreak = 0;
        $currentStreak = 0;

        foreach (CarbonPeriod::create($start, $end) as $date) {
            if ($completedDates->has($date->toDateString())) {
                $currentStreak++;
                $maxStreak = max($maxStreak, $currentStreak);
            } else {
                $currentStreak = 0;
            }
        }

        return $maxStreak;
    }
}
