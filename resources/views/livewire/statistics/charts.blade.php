<div>
    <div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
        <div class="min-w-0">
            <flux:heading size="xl" level="1">Статистика</flux:heading>
            <flux:text class="mt-1">Динамика выполнения привычек</flux:text>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="flex-1 sm:w-40 relative">
                <flux:select wire:model.live="period">
                    <flux:select.option value="7">7 дней</flux:select.option>
                    <flux:select.option value="30">30 дней</flux:select.option>
                </flux:select>
                <div wire:loading wire:target="period" class="absolute right-9 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="size-4 animate-spin text-zinc-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </div>
            </div>
            <flux:button
                :href="route('statistics.export')"
                variant="outline"
                icon="arrow-down-tray"
                size="base"
                tooltip="Экспорт CSV"
            >
                <span class="hidden sm:inline">CSV</span>
            </flux:button>
        </div>
    </div>

    {{-- Heatmap --}}
    <div class="card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <flux:heading size="lg">Активность за 12 недель</flux:heading>
            <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400">
                <span class="hidden sm:inline">Меньше</span>
                <span class="size-3 rounded-sm bg-zinc-100 dark:bg-zinc-800"></span>
                <span class="size-3 rounded-sm bg-emerald-200 dark:bg-emerald-900/50"></span>
                <span class="size-3 rounded-sm bg-emerald-400 dark:bg-emerald-700"></span>
                <span class="size-3 rounded-sm bg-emerald-500 dark:bg-emerald-600"></span>
                <span class="size-3 rounded-sm bg-emerald-600 dark:bg-emerald-500"></span>
                <span class="hidden sm:inline">Больше</span>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0 scrollbar-thin">
            <div class="flex gap-1 min-w-max">
                @foreach ($heatmap['weeks'] as $week)
                    <div class="flex flex-col gap-1">
                        @foreach ($week as $day)
                            @if ($day === null)
                                <div class="size-3 sm:size-4"></div>
                            @else
                                @php
                                    $bgClass = match($day['level']) {
                                        0 => 'bg-zinc-100 dark:bg-zinc-800',
                                        1 => 'bg-emerald-200 dark:bg-emerald-900/50',
                                        2 => 'bg-emerald-400 dark:bg-emerald-700',
                                        3 => 'bg-emerald-500 dark:bg-emerald-600',
                                        4 => 'bg-emerald-600 dark:bg-emerald-500',
                                    };
                                @endphp
                                <div
                                    class="size-3 sm:size-4 rounded-sm {{ $bgClass }} hover:ring-2 hover:ring-indigo-400 transition cursor-default"
                                    title="{{ \Carbon\Carbon::parse($day['date'])->format('d.m.Y') }} — {{ $day['count'] }} {{ trans_choice('отметка|отметки|отметок', $day['count']) }}"
                                ></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div
        x-data="{
            dailyChart: null,
            habitChart: null,
            async initCharts() {
                await window.loadChart();
                const daily = @js($dailyChart);
                const habits = @js($habitChart);
                this.render(daily, habits);
            },
            render(daily, habits) {
                const dailyEl = document.getElementById('dailyChart');
                const habitEl = document.getElementById('habitChart');
                if (!dailyEl || !habitEl || !window.Chart) return;

                if (this.dailyChart) this.dailyChart.destroy();
                if (this.habitChart) this.habitChart.destroy();

                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                const tickColor = isDark ? '#a1a1aa' : '#52525b';

                this.dailyChart = new Chart(dailyEl, {
                    type: 'line',
                    data: {
                        labels: daily.labels,
                        datasets: [{
                            label: '% выполнения',
                            data: daily.data,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { min: 0, max: 100, grid: { color: gridColor }, ticks: { color: tickColor } },
                            x: { grid: { color: gridColor }, ticks: { color: tickColor } },
                        },
                        plugins: { legend: { labels: { color: tickColor } } },
                    },
                });

                this.habitChart = new Chart(habitEl, {
                    type: 'bar',
                    data: {
                        labels: habits.labels,
                        datasets: [{
                            label: 'Отметок',
                            data: habits.data,
                            backgroundColor: habits.colors?.length ? habits.colors : '#6366f1',
                            borderRadius: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { grid: { color: gridColor }, ticks: { color: tickColor } },
                            x: { grid: { color: gridColor }, ticks: { color: tickColor } },
                        },
                        plugins: { legend: { labels: { color: tickColor } } },
                    },
                });
            }
        }"
        x-init="initCharts()"
        @charts-updated.window="render($event.detail.daily, $event.detail.habits)"
        class="space-y-4 sm:space-y-6"
    >
        <div class="card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
            <flux:heading size="lg" class="mb-4">Выполнение по дням (%)</flux:heading>
            <div wire:ignore class="h-56 sm:h-72">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <div class="card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
            <flux:heading size="lg" class="mb-4">Отметки по привычкам</flux:heading>
            <div wire:ignore class="h-56 sm:h-72">
                <canvas id="habitChart"></canvas>
            </div>
        </div>
    </div>
</div>
