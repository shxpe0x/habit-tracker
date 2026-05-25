<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl" level="1">Привычки</flux:heading>
        <flux:button :href="route('habits.create')" wire:navigate variant="primary" icon="plus">
            <span class="hidden sm:inline">Добавить</span>
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

    @if ($habits->isEmpty())
        <div class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 sm:p-10 text-center">
            <flux:icon.check-circle class="mx-auto size-10 text-zinc-400" />
            <flux:heading class="mt-3">Пока нет привычек</flux:heading>
            <flux:text variant="subtle" class="mt-1">Создайте первую привычку, чтобы начать.</flux:text>
            <flux:button :href="route('habits.create')" wire:navigate variant="primary" icon="plus" class="mt-4">
                Создать привычку
            </flux:button>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($habits as $habit)
                <div class="card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 flex items-center gap-3">
                    <span
                        class="size-3 rounded-full shrink-0"
                        style="background-color: {{ $habit->color }}"
                    ></span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-medium truncate">{{ $habit->title }}</span>
                            @if (! $habit->is_active)
                                <flux:badge color="zinc" size="sm">Неактивна</flux:badge>
                            @endif
                        </div>
                        @if ($habit->description)
                            <div class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                {{ $habit->description }}
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-1 shrink-0">
                        <flux:button
                            :href="route('habits.edit', $habit)"
                            wire:navigate
                            variant="ghost"
                            size="sm"
                            icon="pencil-square"
                            square
                            class="min-w-[40px] min-h-[40px]"
                        />
                        <flux:button
                            wire:click="delete({{ $habit->id }})"
                            wire:confirm="Удалить привычку?"
                            variant="ghost"
                            size="sm"
                            icon="trash"
                            square
                            class="min-w-[40px] min-h-[40px] text-red-600! hover:bg-red-50! dark:hover:bg-red-500/10!"
                        />
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $habits->links() }}
        </div>
    @endif
</div>
