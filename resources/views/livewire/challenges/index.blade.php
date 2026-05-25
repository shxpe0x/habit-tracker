<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl" level="1">Челленджи</flux:heading>
        <flux:button :href="route('challenges.create')" wire:navigate variant="primary" icon="plus">
            <span class="hidden sm:inline">Создать</span>
        </flux:button>
    </div>

    @if (session('status'))
        <flux:callout
            variant="success"
            icon="check-circle"
            class="mb-4"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 2500)"
            x-transition.opacity.duration.500ms
        >
            <flux:callout.text>{{ session('status') }}</flux:callout.text>
        </flux:callout>
    @endif

    @if ($challenges->isEmpty())
        <div class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 sm:p-10 text-center">
            <flux:icon.trophy class="mx-auto size-10 text-zinc-400" />
            <flux:heading class="mt-3">Нет челленджей</flux:heading>
            <flux:text variant="subtle" class="mt-1">Поставь себе цель, чтобы было интереснее.</flux:text>
            <flux:button :href="route('challenges.create')" wire:navigate variant="primary" icon="plus" class="mt-4">
                Создать челлендж
            </flux:button>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($challenges as $challenge)
                @php
                    $p = $progress[$challenge->id];
                    $typeLabel = match($challenge->type) {
                        'streak' => 'Серия дней',
                        'total_completions' => 'Всего отметок',
                        'period_days' => 'Активных дней',
                        default => $challenge->type,
                    };
                    $isComplete = $p['percent'] >= 100;
                @endphp
                <div @class([
                    'card-hover-lift rounded-xl border p-4 sm:p-5',
                    'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' => ! $isComplete,
                    'border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/10' => $isComplete,
                ])>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <flux:heading size="lg" class="!mb-0 truncate">{{ $challenge->title }}</flux:heading>
                                @if ($isComplete)
                                    <flux:badge color="lime" size="sm" icon="check">Выполнен</flux:badge>
                                @elseif (! $challenge->is_active)
                                    <flux:badge color="zinc" size="sm">Неактивен</flux:badge>
                                @endif
                            </div>
                            <flux:text size="sm" variant="subtle" class="mt-1">
                                {{ $typeLabel }} · с {{ $challenge->starts_on->format('d.m.Y') }}
                                @if ($challenge->ends_on) — {{ $challenge->ends_on->format('d.m.Y') }} @endif
                            </flux:text>
                            @if ($challenge->description)
                                <flux:text size="sm" class="mt-2 line-clamp-2">{{ $challenge->description }}</flux:text>
                            @endif
                        </div>
                        <div class="flex gap-1 shrink-0">
                            <flux:button :href="route('challenges.edit', $challenge)" wire:navigate variant="ghost" size="sm" icon="pencil-square" square class="min-w-[40px] min-h-[40px]" />
                            <flux:button
                                wire:click="delete({{ $challenge->id }})"
                                wire:confirm="Удалить челлендж?"
                                variant="ghost"
                                size="sm"
                                icon="trash"
                                square
                                class="min-w-[40px] min-h-[40px] text-red-600! hover:bg-red-50! dark:hover:bg-red-500/10!"
                            />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-sm">
                            <flux:text variant="subtle">Прогресс</flux:text>
                            <flux:text class="font-medium tabular-nums">
                                {{ $p['current'] }} / {{ $p['target'] }}
                                <span class="text-zinc-400">({{ $p['percent'] }}%)</span>
                            </flux:text>
                        </div>
                        <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-700 overflow-hidden">
                            <div
                                @class([
                                    'h-full rounded-full transition-all duration-500',
                                    'bg-indigo-600' => ! $isComplete,
                                    'bg-emerald-500' => $isComplete,
                                ])
                                style="width: {{ $p['percent'] }}%"
                            ></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $challenges->links() }}</div>
    @endif
</div>
