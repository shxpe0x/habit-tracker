<?php

namespace App\Services;

use App\Models\HabitLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserStreakService
{
    /**
     * Кэш дат на время одного запроса.
     * Ключ — id пользователя, значение — collection с датами в виде flip-map.
     *
     * @var array<int, Collection>
     */
    protected array $datesCache = [];

    public function current(User $user): int
    {
        $dates = $this->completedDates($user);

        if ($dates->isEmpty()) {
            return 0;
        }

        $cursor = today();

        // Если сегодня не отмечен — считаем со вчера (стрик ещё «живой»)
        if (! $dates->has($cursor->toDateString())) {
            $cursor = $cursor->copy()->subDay();
            if (! $dates->has($cursor->toDateString())) {
                return 0;
            }
        }

        $streak = 0;
        while ($dates->has($cursor->toDateString())) {
            $streak++;
            $cursor = $cursor->copy()->subDay();
        }

        return $streak;
    }

    public function longest(User $user): int
    {
        $dates = $this->completedDates($user)->keys()->sort()->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $longest = 1;
        $current = 1;
        $previous = Carbon::parse($dates->first());

        foreach ($dates->slice(1) as $dateStr) {
            $date = Carbon::parse($dateStr);
            // Сравниваем строго через прибавление дня — корректно работает для всех случаев
            if ($previous->copy()->addDay()->isSameDay($date)) {
                $current++;
                $longest = max($longest, $current);
            } else {
                $current = 1;
            }
            $previous = $date;
        }

        return $longest;
    }

    /**
     * @return Collection<string, true>
     */
    protected function completedDates(User $user): Collection
    {
        if (isset($this->datesCache[$user->id])) {
            return $this->datesCache[$user->id];
        }

        // Загружаем только последние 2 года — этого более чем достаточно для streak.
        // Если у пользователя десятки тысяч записей за годы — экономим память.
        return $this->datesCache[$user->id] = HabitLog::query()
            ->whereHas('habit', fn ($q) => $q->where('user_id', $user->id))
            ->whereDate('completed_on', '>=', now()->subYears(2)->toDateString())
            ->pluck('completed_on')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->flip();
    }
}
