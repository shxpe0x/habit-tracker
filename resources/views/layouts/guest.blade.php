<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Habit Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-full bg-white dark:bg-zinc-950 antialiased">
        <div class="aurora-bg min-h-screen flex flex-col items-center justify-center px-4 py-12 relative">
            <a href="/" wire:navigate class="flex items-center gap-3 mb-8 relative z-10">
                <div class="flex items-center justify-center h-10 w-10 rounded-md bg-indigo-600 text-white text-lg font-bold shadow-lg shadow-indigo-600/20">Т</div>
                <flux:heading size="lg">Трекер привычек</flux:heading>
            </a>

            <div
                class="w-full max-w-md rounded-xl border border-zinc-200/80 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-6 sm:p-8 shadow-xl shadow-zinc-900/5 relative z-10"
                style="animation: var(--animate-slide-up)"
            >
                {{ $slot }}
            </div>
        </div>

        @fluxScripts
    </body>
</html>
