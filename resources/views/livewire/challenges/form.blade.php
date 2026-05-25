<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">
            {{ $challenge ? 'Редактировать челлендж' : 'Новый челлендж' }}
        </flux:heading>
        <flux:text class="mt-1">
            Поставь цель и наблюдай прогресс
        </flux:text>
    </div>

    <div class="max-w-3xl space-y-6">
        @if (! $challenge)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon.trophy class="size-5 text-indigo-600" />
                    <flux:heading size="lg" class="!mb-0">Готовые челленджи</flux:heading>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($presets as $i => $preset)
                        <button
                            type="button"
                            wire:click="applyPreset({{ $i }})"
                            class="spotlight-card group text-left rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-500 bg-white dark:bg-zinc-900 p-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/10 active:translate-y-0"
                        >
                            <div class="font-medium group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $preset['title'] }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 line-clamp-2">
                                {{ $preset['description'] }}
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <flux:badge size="sm" color="indigo">{{ $preset['target_value'] }}</flux:badge>
                                <flux:text size="sm" variant="subtle">
                                    {{ match($preset['type']) {
                                        'streak' => 'дней подряд',
                                        'total_completions' => 'отметок',
                                        'period_days' => 'активных дней',
                                    } }}
                                </flux:text>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <form wire:submit="save" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6 space-y-5">
            <flux:input
                wire:model="title"
                label="Название"
                placeholder="Например: Месяц без сладкого"
                required
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="type" label="Тип цели">
                    @foreach ($typeLabels as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input
                    wire:model="target_value"
                    label="Целевое значение"
                    type="number"
                    min="1"
                    max="365"
                />
            </div>

            @if (! $showAdvanced)
                <button
                    type="button"
                    wire:click="$toggle('showAdvanced')"
                    class="text-sm text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 inline-flex items-center gap-1"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Дополнительные параметры
                </button>
            @else
                <div class="flex items-center justify-between">
                    <flux:text size="sm" variant="subtle">Дополнительные параметры</flux:text>
                    <button
                        type="button"
                        wire:click="$toggle('showAdvanced')"
                        class="text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300"
                    >
                        Свернуть
                    </button>
                </div>
                <flux:textarea
                    wire:model="description"
                    label="Описание (необязательно)"
                    placeholder="Зачем нужен этот челлендж"
                    rows="2"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="starts_on" label="Начало" type="date" />
                    <flux:input wire:model="ends_on" label="Конец (необязательно)" type="date" />
                </div>

                <flux:switch wire:model="is_active" label="Активен" />

                @if ($habits->isNotEmpty())
                    <flux:field>
                        <flux:label>Привычки</flux:label>
                        <flux:description>Если ничего не выбрано — учитываются все активные</flux:description>
                        <div class="mt-2 max-h-56 overflow-y-auto rounded-md border border-zinc-200 dark:border-zinc-700 p-3">
                            <flux:checkbox.group wire:model="selectedHabits">
                                @foreach ($habits as $h)
                                    <flux:checkbox value="{{ $h->id }}" :label="$h->title" />
                                @endforeach
                            </flux:checkbox.group>
                        </div>
                    </flux:field>
                @endif
            @endif

            <flux:separator variant="subtle" />

            <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                <flux:button :href="route('challenges.index')" wire:navigate variant="ghost">Отмена</flux:button>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Сохранить</span>
                    <span wire:loading wire:target="save">Сохранение...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>
