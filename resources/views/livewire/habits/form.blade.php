<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">
            {{ $habit ? 'Редактировать привычку' : 'Новая привычка' }}
        </flux:heading>
        <flux:text class="mt-1">
            {{ $habit ? 'Измените параметры привычки' : 'Выберите готовый шаблон или создайте свой' }}
        </flux:text>
    </div>

    <div class="max-w-3xl space-y-6">
        @if (! $habit)
            {{-- Пресеты --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon.sparkles class="size-5 text-indigo-600" />
                    <flux:heading size="lg" class="!mb-0">Готовые шаблоны</flux:heading>
                </div>

                {{-- Категории --}}
                <div class="flex gap-2 overflow-x-auto pb-2 mb-4 -mx-4 sm:-mx-6 px-4 sm:px-6 scrollbar-thin">
                    @foreach ($categories as $key => $cat)
                        <button
                            type="button"
                            wire:click="setCategory('{{ $key }}')"
                            @class([
                                'shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition',
                                'bg-indigo-600 text-white' => $activeCategory === $key,
                                'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' => $activeCategory !== $key,
                            ])
                        >
                            {{ $cat['name'] }}
                        </button>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($presetsByCategory[$activeCategory] ?? [] as $preset)
                        <button
                            type="button"
                            wire:click="applyPreset({{ $preset['index'] }})"
                            class="spotlight-card group text-left flex items-start gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-500 bg-white dark:bg-zinc-900 p-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/10 active:translate-y-0"
                        >
                            <span
                                class="mt-1 size-3 rounded-full shrink-0 transition group-hover:scale-125"
                                style="background-color: {{ $preset['color'] }}"
                            ></span>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $preset['title'] }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate mt-0.5">
                                    {{ $preset['description'] }}
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Форма --}}
        <form wire:submit="save" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6 space-y-5">
            <flux:input
                wire:model="title"
                label="Название"
                placeholder="Например: Чтение 30 минут"
                required
            />

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
                    placeholder="Зачем нужна эта привычка"
                    rows="2"
                />

                <flux:field>
                    <flux:label>Время дня</flux:label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach (\App\Models\Habit::TIME_LABELS as $value => $label)
                            <button
                                type="button"
                                wire:click="$set('time_of_day', '{{ $value }}')"
                                @class([
                                    'rounded-lg border p-2.5 text-sm font-medium transition flex items-center justify-center gap-1.5',
                                    'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300' => $time_of_day === $value,
                                    'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' => $time_of_day !== $value,
                                ])
                            >
                                @if (\App\Models\Habit::TIME_ICONS[$value])
                                    <span>{{ \App\Models\Habit::TIME_ICONS[$value] }}</span>
                                @endif
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </flux:field>

                <flux:field>
                    <flux:label>Цвет</flux:label>
                    <input
                        wire:model="color"
                        type="color"
                        class="block h-10 w-20 rounded-md border border-zinc-200 dark:border-zinc-700 cursor-pointer"
                    />
                    <flux:error name="color" />
                </flux:field>

                <flux:switch
                    wire:model="is_active"
                    label="Активна"
                    description="Неактивные привычки не показываются на странице «Сегодня»"
                />
            @endif

            <flux:separator variant="subtle" />

            <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                <flux:button :href="route('habits.index')" wire:navigate variant="ghost" class="sm:w-auto">
                    Отмена
                </flux:button>
                <flux:button type="submit" variant="primary" class="sm:w-auto" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Сохранить</span>
                    <span wire:loading wire:target="save">Сохранение...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>
