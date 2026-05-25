<div class="space-y-6">
    {{-- Hero: streak + мотивация --}}
    <div class="spotlight-card rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-gradient-to-br from-orange-50 to-white dark:from-zinc-900 dark:to-zinc-900 p-5 sm:p-7">
        <div class="flex items-start gap-4 sm:gap-6">
            <div class="relative shrink-0">
                <div @class([
                    'flex items-center justify-center size-16 sm:size-20 rounded-2xl transition',
                    'bg-orange-500 text-white shadow-lg shadow-orange-500/30' => $streak > 0 && ! $streakInDanger,
                    'bg-zinc-200 dark:bg-zinc-800 text-zinc-400' => $streak === 0,
                    'bg-amber-400 text-white animate-pulse' => $streakInDanger,
                ])>
                    <svg class="size-9 sm:size-11" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M13.5 0.67c.7 4.5 1.7 9.4-1 12.7c-2.4 2.9-4.7 4.9-2.6 8.7C8.6 19 13.5 17.5 13.5 12c0-2.5-1.5-3.8-1.5-3.8c.5 4.3-2.4 5-2.4 5C9.4 11.4 8 8.7 8 5.4c0-3.4 5.5-4.7 5.5-4.7z"/>
                    </svg>
                </div>
                @if ($streak > 0)
                    <flux:badge size="sm" color="zinc" class="absolute -top-2 -right-2 shadow">
                        {{ $streak }}
                    </flux:badge>
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <flux:heading size="lg" class="!mb-0">
                        @if ($streak > 0)
                            {{ $streak }} {{ trans_choice('день|дня|дней', $streak) }} подряд
                        @else
                            Старт серии
                        @endif
                    </flux:heading>
                    @if ($streakInDanger)
                        <span class="star-border inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 text-xs font-medium">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                            Серия в опасности
                        </span>
                    @endif
                </div>
                <flux:text class="mt-1">{{ $motivation }}</flux:text>

                @if ($longestStreak > 0)
                    <flux:text size="sm" variant="subtle" class="mt-2">
                        Лучшая серия: {{ $longestStreak }} {{ trans_choice('день|дня|дней', $longestStreak) }}
                    </flux:text>
                @endif
            </div>
        </div>

        @if ($todayTotal > 0)
            <div class="mt-5">
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <flux:text variant="subtle">Сегодня</flux:text>
                    <flux:text class="font-medium tabular-nums">{{ $todayDone }} / {{ $todayTotal }}</flux:text>
                </div>
                <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-orange-400 to-orange-600 transition-all duration-500"
                        style="width: {{ $todayPercent }}%"
                    ></div>
                </div>
            </div>
        @endif
    </div>

    {{-- Привычки --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">Привычки на сегодня</flux:heading>
            <flux:button :href="route('habits.create')" wire:navigate variant="primary" icon="plus" size="sm">
                <span class="hidden sm:inline">Добавить</span>
            </flux:button>
        </div>

        @if ($habits->isEmpty())
            <div class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 sm:p-10 text-center">
                <flux:icon.sparkles class="mx-auto size-10 text-zinc-400" />
                <flux:heading class="mt-3">Начни с одной привычки</flux:heading>
                <flux:text variant="subtle" class="mt-1 max-w-md mx-auto">
                    Выбери из готовых шаблонов или создай свою. Достаточно одной — главное регулярность.
                </flux:text>
                <flux:button :href="route('habits.create')" wire:navigate variant="primary" icon="sparkles" class="mt-4">
                    Добавить привычку
                </flux:button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($habits as $habit)
                    @php $isDone = in_array($habit->id, $completedIds); @endphp
                    <button
                        type="button"
                        wire:click="toggleToday({{ $habit->id }})"
                        wire:loading.attr="disabled"
                        @class([
                            'group relative w-full text-left rounded-xl border p-4 transition-all duration-200 active:scale-[0.98]',
                            'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 hover:border-zinc-300 dark:hover:border-zinc-600 hover:-translate-y-0.5 hover:shadow-md hover:shadow-zinc-900/5' => ! $isDone,
                            'border-emerald-500/50 bg-emerald-50 dark:bg-emerald-500/10' => $isDone,
                        ])
                    >
                        <div class="flex items-center gap-3">
                            <span
                                class="size-3 rounded-full shrink-0"
                                style="background-color: {{ $habit->color }}"
                            ></span>
                            <div class="min-w-0 flex-1">
                                <div @class([
                                    'font-medium truncate flex items-center gap-1.5',
                                    'line-through text-zinc-500 dark:text-zinc-400' => $isDone,
                                ])>
                                    @if ($icon = \App\Models\Habit::TIME_ICONS[$habit->time_of_day ?? 'any'] ?? null)
                                        <span class="text-sm">{{ $icon }}</span>
                                    @endif
                                    <span class="truncate">{{ $habit->title }}</span>
                                </div>
                                @if ($habit->description)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 truncate">
                                        {{ $habit->description }}
                                    </div>
                                @endif
                            </div>
                            <div @class([
                                'size-8 rounded-full flex items-center justify-center shrink-0 transition',
                                'bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700' => ! $isDone,
                                'bg-emerald-500 text-white' => $isDone,
                            ])>
                                @if ($isDone)
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Сводная статистика --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text size="sm" variant="subtle">Привычек</flux:text>
            <flux:heading class="mt-1">{{ $summary['active_habits'] }}</flux:heading>
        </div>
        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text size="sm" variant="subtle">Сегодня</flux:text>
            <flux:heading class="mt-1">{{ $summary['completed_today'] }}/{{ $summary['active_habits'] }}</flux:heading>
        </div>
        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text size="sm" variant="subtle">Серия</flux:text>
            <flux:heading class="mt-1">{{ $streak }}</flux:heading>
        </div>
        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <flux:text size="sm" variant="subtle">Среднее за 7д</flux:text>
            <flux:heading class="mt-1">{{ $summary['week_avg_percent'] }}%</flux:heading>
        </div>
    </div>
</div>
