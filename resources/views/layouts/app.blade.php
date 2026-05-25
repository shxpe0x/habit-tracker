<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#fafafa" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">

        <title>{{ config('app.name', 'Habit Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="aurora-bg-subtle min-h-full bg-zinc-50 dark:bg-zinc-950 antialiased">
        {{-- Десктопная шапка --}}
        <flux:header container class="hidden sm:flex bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
            <flux:brand href="{{ route('dashboard') }}" name="Трекер" wire:navigate>
                <x-slot name="logo">
                    <div class="flex items-center justify-center h-8 w-8 rounded-md bg-indigo-600 text-white text-sm font-bold">Т</div>
                </x-slot>
            </flux:brand>

            <flux:navbar class="-mb-px ms-6">
                <flux:navbar.item icon="calendar-days" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    Сегодня
                </flux:navbar.item>
                <flux:navbar.item icon="check-circle" :href="route('habits.index')" :current="request()->routeIs('habits.*')" wire:navigate>
                    Привычки
                </flux:navbar.item>
                <flux:navbar.item icon="trophy" :href="route('challenges.index')" :current="request()->routeIs('challenges.*')" wire:navigate>
                    Челленджи
                </flux:navbar.item>
                <flux:navbar.item icon="chart-bar" :href="route('statistics')" :current="request()->routeIs('statistics')" wire:navigate>
                    Статистика
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <livewire:layout.user-menu />
        </flux:header>

        {{-- Мобильная шапка (без меню — навигация снизу) --}}
        <header class="sm:hidden sticky top-0 z-30 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-b border-zinc-200 dark:border-zinc-800 px-4 h-14 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                <div class="flex items-center justify-center h-7 w-7 rounded-md bg-indigo-600 text-white text-xs font-bold">Т</div>
                <span class="font-semibold">Трекер</span>
            </a>
            <livewire:layout.user-menu />
        </header>

        {{-- Контент --}}
        <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8 pb-24 sm:pb-8">
            {{ $slot ?? '' }}
        </main>

        {{-- Мобильная навигация снизу --}}
        <nav class="sm:hidden fixed bottom-0 left-0 right-0 z-30 bg-white/95 dark:bg-zinc-900/95 backdrop-blur border-t border-zinc-200 dark:border-zinc-800 pb-[env(safe-area-inset-bottom)]">
            <div class="grid grid-cols-4">
                @php
                    $tabs = [
                        ['route' => 'dashboard', 'label' => 'Сегодня', 'icon' => 'M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'pattern' => 'dashboard'],
                        ['route' => 'habits.index', 'label' => 'Привычки', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'pattern' => 'habits.*'],
                        ['route' => 'challenges.index', 'label' => 'Цели', 'icon' => 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0', 'pattern' => 'challenges.*'],
                        ['route' => 'statistics', 'label' => 'Графики', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z', 'pattern' => 'statistics'],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    @php $active = request()->routeIs($tab['pattern']); @endphp
                    <a
                        href="{{ route($tab['route']) }}"
                        wire:navigate
                        @class([
                            'flex flex-col items-center justify-center gap-0.5 py-2.5 min-h-[56px] transition active:bg-zinc-100 dark:active:bg-zinc-800',
                            'text-indigo-600 dark:text-indigo-400' => $active,
                            'text-zinc-500 dark:text-zinc-400' => ! $active,
                        ])
                    >
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
                        </svg>
                        <span class="text-[11px] font-medium">{{ $tab['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        @fluxScripts
    </body>
</html>
