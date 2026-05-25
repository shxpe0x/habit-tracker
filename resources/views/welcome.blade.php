<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ config('app.name', 'Habit Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-full bg-white dark:bg-zinc-950 antialiased">
        <div
            x-data="{ navOpen: false }"
            class="aurora-bg min-h-screen flex flex-col"
        >
            {{-- Card Nav: круглая кнопка слева посередине + выезжающее меню --}}
            <div class="fixed left-3 sm:left-5 top-1/2 -translate-y-1/2 z-40">
                <button
                    type="button"
                    @click="navOpen = !navOpen"
                    aria-label="Открыть меню"
                    :aria-expanded="navOpen"
                    class="relative size-11 sm:size-12 rounded-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-lg shadow-zinc-900/10 hover:shadow-xl hover:scale-105 active:scale-95 transition flex items-center justify-center"
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         x-show="!navOpen" x-transition.opacity>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         x-show="navOpen" x-transition.opacity x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Backdrop --}}
            <div
                x-show="navOpen"
                @click="navOpen = false"
                x-transition.opacity.duration.300ms
                x-cloak
                class="fixed inset-0 z-30 bg-zinc-900/30 dark:bg-black/50 backdrop-blur-sm"
            ></div>

            {{-- Card Nav Panel --}}
            <aside
                x-show="navOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-4"
                @keydown.escape.window="navOpen = false"
                x-cloak
                class="fixed left-3 sm:left-20 top-1/2 -translate-y-1/2 z-40 w-[min(280px,calc(100vw-1.5rem))]"
            >
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/95 backdrop-blur shadow-2xl shadow-zinc-900/20 p-2">
                    @php
                        $navItems = [
                            ['href' => route('about'), 'icon' => 'information-circle', 'label' => 'About', 'desc' => 'Подробно о проекте'],
                            ['href' => auth()->check() ? route('dashboard') : route('login'), 'icon' => 'rocket-launch', 'label' => auth()->check() ? 'Открыть' : 'Войти', 'desc' => auth()->check() ? 'Перейти в приложение' : 'У меня есть аккаунт'],
                        ];
                        if (! auth()->check()) {
                            $navItems[] = ['href' => route('register'), 'icon' => 'sparkles', 'label' => 'Регистрация', 'desc' => 'Создать аккаунт'];
                        }
                    @endphp

                    @foreach ($navItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            wire:navigate
                            class="spotlight-card group flex items-center gap-3 rounded-xl px-3 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                        >
                            <div class="size-10 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                                <flux:icon :icon="$item['icon']" class="size-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium">{{ $item['label'] }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $item['desc'] }}</div>
                            </div>
                            <svg class="size-4 text-zinc-400 group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </aside>

            <header class="px-4 sm:px-6 py-4 sm:py-6 flex items-center justify-between relative z-10">
                <a href="/" wire:navigate class="flex items-center gap-2 sm:gap-3 group">
                    <div class="flex items-center justify-center h-9 w-9 sm:h-10 sm:w-10 rounded-md bg-indigo-600 text-white text-base sm:text-lg font-bold shadow-lg shadow-indigo-600/20 transition-transform group-hover:scale-105">Т</div>
                    <flux:heading size="lg" class="!mb-0 hidden sm:block">Трекер привычек</flux:heading>
                </a>

                <div class="flex items-center gap-2">
                    @auth
                        <flux:button :href="route('dashboard')" wire:navigate variant="primary" size="sm">
                            Открыть
                        </flux:button>
                    @else
                        <flux:button :href="route('login')" wire:navigate variant="ghost" size="sm">Войти</flux:button>
                        <flux:button :href="route('register')" wire:navigate variant="primary" size="sm">Регистрация</flux:button>
                    @endauth
                </div>
            </header>

            <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12 relative z-10">
                <div class="max-w-3xl w-full text-center" style="animation: var(--animate-slide-up)">
                    {{-- TextType — заголовок с эффектом печати --}}
                    <flux:heading
                        size="xl"
                        level="1"
                        class="text-3xl sm:text-5xl font-bold tracking-tight min-h-[3.5rem] sm:min-h-[4.5rem]"
                    >
                        <span
                            x-data="textType({
                                texts: [
                                    'Маленькие привычки.',
                                    'Большие результаты.',
                                    'Один день за раз.',
                                    'Серия не должна прерываться.'
                                ],
                                typingSpeed: 70,
                                deletingSpeed: 35,
                                pauseDuration: 2000,
                            })"
                        >
                            <span x-text="display"></span><span class="text-type-cursor"></span>
                        </span>
                    </flux:heading>

                    <flux:text class="mt-5 sm:mt-6 text-base sm:text-lg max-w-xl mx-auto">
                        Отмечай привычки одним тапом, держи серию, ставь цели и наблюдай прогресс на графиках.
                    </flux:text>

                    <div class="mt-8 sm:mt-10 flex items-center justify-center gap-3 flex-wrap">
                        @guest
                            <flux:button :href="route('register')" wire:navigate variant="primary" size="base" icon="rocket-launch">
                                Начать бесплатно
                            </flux:button>
                            <flux:button :href="route('login')" wire:navigate variant="ghost" size="base">
                                У меня уже есть аккаунт
                            </flux:button>
                        @endguest
                    </div>

                    <div class="mt-12 sm:mt-16 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-left">
                        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200/80 dark:border-zinc-800/80 bg-white/70 dark:bg-zinc-900/70 backdrop-blur p-5">
                            <flux:icon.check-circle class="size-7 text-indigo-600" />
                            <flux:heading class="mt-3">Привычки</flux:heading>
                            <flux:text size="sm" variant="subtle" class="mt-1">
                                Готовые шаблоны и быстрая отметка одним тапом.
                            </flux:text>
                        </div>
                        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200/80 dark:border-zinc-800/80 bg-white/70 dark:bg-zinc-900/70 backdrop-blur p-5">
                            <flux:icon.fire class="size-7 text-orange-500" />
                            <flux:heading class="mt-3">Серии</flux:heading>
                            <flux:text size="sm" variant="subtle" class="mt-1">
                                Считаем дни подряд. Не дай огоньку погаснуть.
                            </flux:text>
                        </div>
                        <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200/80 dark:border-zinc-800/80 bg-white/70 dark:bg-zinc-900/70 backdrop-blur p-5">
                            <flux:icon.chart-bar class="size-7 text-indigo-600" />
                            <flux:heading class="mt-3">Графики</flux:heading>
                            <flux:text size="sm" variant="subtle" class="mt-1">
                                Визуальный прогресс по дням и привычкам.
                            </flux:text>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="px-6 py-6 text-center relative z-10">
                <flux:text size="sm" variant="subtle">
                    <a href="{{ route('about') }}" wire:navigate class="hover:underline">О проекте</a>
                    <span class="mx-2">·</span>
                    Laravel · Livewire · Flux UI
                </flux:text>
            </footer>
        </div>

        @fluxScripts
    </body>
</html>
