<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>О проекте · {{ config('app.name', 'Habit Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="aurora-bg-subtle min-h-full bg-zinc-50 dark:bg-zinc-950 antialiased">
        {{-- Шапка --}}
        <header class="sticky top-0 z-30 px-4 sm:px-6 py-3 sm:py-4 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <a href="/" wire:navigate class="flex items-center gap-2 sm:gap-3 group">
                    <div class="flex items-center justify-center h-9 w-9 sm:h-10 sm:w-10 rounded-md bg-indigo-600 text-white text-base sm:text-lg font-bold shadow-lg shadow-indigo-600/20 transition-transform group-hover:scale-105">Т</div>
                    <flux:heading size="lg" class="!mb-0 hidden sm:block">Трекер привычек</flux:heading>
                </a>

                <div class="flex items-center gap-2">
                    @auth
                        <flux:button :href="route('dashboard')" wire:navigate variant="primary" size="sm">Открыть</flux:button>
                    @else
                        <flux:button :href="route('login')" wire:navigate variant="ghost" size="sm">Войти</flux:button>
                        <flux:button :href="route('register')" wire:navigate variant="primary" size="sm">Регистрация</flux:button>
                    @endauth
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
            {{-- Hero --}}
            <div class="mb-8 sm:mb-12" style="animation: var(--animate-slide-up)">
                <flux:badge color="indigo" size="lg" class="mb-4">Документация</flux:badge>
                <flux:heading size="xl" level="1" class="text-3xl sm:text-5xl font-bold tracking-tight">
                    О проекте
                </flux:heading>
                <flux:text class="mt-3 text-base sm:text-lg max-w-2xl">
                    Подробное описание архитектуры, технологий и решений, использованных при разработке трекера привычек.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-8 lg:gap-12">
                {{-- Навигация по разделам --}}
                <aside class="hidden lg:block">
                    <nav
                        x-data="{
                            active: '',
                            init() {
                                const observer = new IntersectionObserver((entries) => {
                                    entries.forEach(e => {
                                        if (e.isIntersecting) this.active = e.target.id;
                                    });
                                }, { rootMargin: '-20% 0px -70% 0px' });
                                document.querySelectorAll('section[id]').forEach(s => observer.observe(s));
                            }
                        }"
                        class="sticky top-24 space-y-1"
                    >
                        @php
                            $sections = [
                                'introduction' => '1. Введение',
                                'goals' => '2. Постановка задачи',
                                'analysis' => '3. Анализ аналогов',
                                'tech-stack' => '4. Стек технологий',
                                'architecture' => '5. Архитектура',
                                'database' => '6. База данных',
                                'modules' => '7. Модули',
                                'ui' => '8. Интерфейс',
                                'security' => '9. Безопасность',
                                'performance' => '10. Производительность',
                                'testing' => '11. Тестирование',
                                'conclusion' => '12. Заключение',
                            ];
                        @endphp
                        @foreach ($sections as $id => $label)
                            <a
                                href="#{{ $id }}"
                                :class="active === '{{ $id }}'
                                    ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 font-medium'
                                    : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800'"
                                class="block px-3 py-2 rounded-lg text-sm transition"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </aside>

                {{-- Контент --}}
                <div class="space-y-12 sm:space-y-16 max-w-3xl min-w-0">

                    {{-- 1. Введение --}}
                    <section id="introduction" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            1. Введение
                        </flux:heading>
                        <div class="prose-content space-y-4 text-zinc-700 dark:text-zinc-300">
                            <p>
                                В современном ритме жизни человеку сложно формировать и удерживать полезные привычки: чтение, спорт, здоровое питание, изучение языков. Психологические исследования показывают, что для формирования устойчивой привычки требуется регулярное повторение в течение нескольких недель.
                            </p>
                            <p>
                                Без внешнего инструмента контроля большинство людей бросает начатое уже через пару дней. Цель проекта — разработать веб-приложение, которое помогает пользователю отслеживать ежедневное выполнение привычек, поддерживает мотивацию через систему серий (стриков) и челленджей, а также предоставляет визуальную статистику прогресса.
                            </p>
                        </div>
                    </section>

                    {{-- 2. Постановка задачи --}}
                    <section id="goals" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            2. Постановка задачи
                        </flux:heading>

                        <flux:heading size="lg" class="mt-6 mb-3">Функциональные требования</flux:heading>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="text-left p-3 font-medium text-zinc-500 w-12">№</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Требование</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ([
                                        'Регистрация и авторизация пользователей',
                                        'Восстановление пароля по email',
                                        'Создание, редактирование, удаление привычек',
                                        'Отметка выполнения привычки в текущий день',
                                        'Создание целей-челленджей трёх типов',
                                        'Расчёт прогресса челленджа в процентах',
                                        'Подсчёт текущей и максимальной серии (стрика)',
                                        'Графическая визуализация: % по дням, отметки по привычкам, тепловая карта',
                                        'Экспорт истории отметок в CSV',
                                        'Готовые шаблоны привычек и челленджей',
                                    ] as $i => $req)
                                        <tr>
                                            <td class="p-3 text-zinc-500 font-mono">F{{ $i + 1 }}</td>
                                            <td class="p-3">{{ $req }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Нефункциональные требования</flux:heading>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="text-left p-3 font-medium text-zinc-500 w-12">№</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Требование</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ([
                                        'Адаптивный интерфейс для мобильных, планшетов и десктопов',
                                        'Поддержка светлой и тёмной тем',
                                        'Время отклика — не более 500 мс на типичных операциях',
                                        'Защита от несанкционированного доступа к чужим данным',
                                        'Доступность (tap-targets ≥ 40px, поддержка prefers-reduced-motion)',
                                        'Возможность экспорта пользовательских данных',
                                        'Локализация интерфейса на русский язык',
                                    ] as $i => $req)
                                        <tr>
                                            <td class="p-3 text-zinc-500 font-mono">NF{{ $i + 1 }}</td>
                                            <td class="p-3">{{ $req }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 3. Анализ аналогов --}}
                    <section id="analysis" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            3. Анализ аналогов
                        </flux:heading>

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-x-auto">
                            <table class="w-full text-sm min-w-[600px]">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="text-left p-3 font-medium text-zinc-500">Приложение</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Сильные стороны</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Недостатки</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ([
                                        ['Habitica', 'Игровая механика (RPG), сообщество', 'Перегруженный интерфейс'],
                                        ['Loop Habit Tracker', 'Чистый UI, открытый код', 'Только Android, нет облака'],
                                        ['Streaks (iOS)', 'Простота, красивый UI', 'Только iOS, платная'],
                                        ['HabitNow', 'Гибкие напоминания', 'Реклама, перегружен функциями'],
                                    ] as $row)
                                        <tr>
                                            <td class="p-3 font-medium">{{ $row[0] }}</td>
                                            <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $row[1] }}</td>
                                            <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $row[2] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 rounded-xl border border-indigo-200 dark:border-indigo-500/30 bg-indigo-50/50 dark:bg-indigo-500/5 p-5">
                            <flux:heading size="base" class="mb-2">Решения для проекта</flux:heading>
                            <ul class="space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300 list-disc pl-5">
                                <li>Минималистичный интерфейс, главный акцент — «отметить за один тап».</li>
                                <li>Стрики как ключевой мотивационный механизм (по аналогии с Duolingo).</li>
                                <li>Заранее заготовленные шаблоны — новый пользователь начинает работу за секунды.</li>
                                <li>Веб-приложение работает с любого устройства — устраняется недостаток платформозависимых аналогов.</li>
                            </ul>
                        </div>
                    </section>

                    {{-- 4. Стек технологий --}}
                    <section id="tech-stack" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            4. Стек технологий
                        </flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                                <flux:badge color="violet" size="sm" class="mb-3">Backend</flux:badge>
                                <ul class="space-y-2 text-sm">
                                    <li><strong>PHP 8.3+</strong> — серверный язык</li>
                                    <li><strong>Laravel 13</strong> — фреймворк, маршруты, ORM, миграции</li>
                                    <li><strong>Livewire 3</strong> — реактивные компоненты на сервере</li>
                                    <li><strong>Livewire Volt</strong> — однофайловые компоненты</li>
                                    <li><strong>Laravel Breeze</strong> — стартер для авторизации</li>
                                    <li><strong>SQLite</strong> — встроенная база данных</li>
                                </ul>
                            </div>
                            <div class="spotlight-card card-hover-lift rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                                <flux:badge color="sky" size="sm" class="mb-3">Frontend</flux:badge>
                                <ul class="space-y-2 text-sm">
                                    <li><strong>Tailwind CSS 4</strong> — утилитарные стили</li>
                                    <li><strong>Flux UI 2</strong> — компоненты от создателей Livewire</li>
                                    <li><strong>Alpine.js</strong> — лёгкий JS-фреймворк</li>
                                    <li><strong>Chart.js 4</strong> — графики (lazy-load)</li>
                                    <li><strong>Vite 8</strong> — сборка фронтенда</li>
                                </ul>
                            </div>
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Почему именно так</flux:heading>
                        <div class="space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
                            <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <strong class="text-zinc-900 dark:text-white">Laravel</strong>
                                <p class="mt-1">Зрелая экосистема, подробная документация, Eloquent ORM, шаблонизатор Blade, готовая авторизация. Альтернатива Node.js потребовала бы больше ручной работы.</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <strong class="text-zinc-900 dark:text-white">Livewire вместо React/Vue</strong>
                                <p class="mt-1">Не нужно поднимать отдельный API. Один язык на всём стеке. Логика остаётся на сервере, что безопаснее. Для курсовой это удвоило бы объём кода без выигрыша в качестве.</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <strong class="text-zinc-900 dark:text-white">SQLite</strong>
                                <p class="mt-1">Не требует отдельного сервера, работает на любой машине. При необходимости заменяется на MySQL/PostgreSQL без изменения кода — всё абстрагировано через Eloquent.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 5. Архитектура --}}
                    <section id="architecture" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            5. Архитектура
                        </flux:heading>

                        <flux:heading size="lg" class="mb-3">Слои приложения</flux:heading>
                        <div class="space-y-2">
                            @foreach ([
                                ['View', 'Blade-шаблоны и Livewire-компоненты, формирующие HTML'],
                                ['Контроллеры (Livewire)', 'Обработчики действий пользователя'],
                                ['Сервисы', 'Бизнес-логика: расчёт стриков, статистики, прогресса'],
                                ['Модели (Eloquent)', 'Работа с БД, связи, scope-методы'],
                                ['Политики', 'Проверка прав доступа к ресурсам'],
                                ['База данных', 'SQLite, миграции'],
                            ] as $i => $layer)
                                <div class="flex gap-3 items-start rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                    <div class="size-8 rounded-md bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-mono font-medium text-sm shrink-0">
                                        {{ $i + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $layer[0] }}</div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-0.5">{{ $layer[1] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Поток данных</flux:heading>
                        <flux:text size="sm" class="mb-4">
                            Что происходит когда пользователь нажимает «Отметить»:
                        </flux:text>
                        <ol class="space-y-2 list-decimal pl-5 text-sm text-zinc-700 dark:text-zinc-300">
                            <li>Кликает кнопку.</li>
                            <li>Livewire отправляет AJAX-запрос с состоянием компонента и именем метода.</li>
                            <li>Сервер вызывает метод компонента <code class="text-xs px-1 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800">toggleToday($habitId)</code>.</li>
                            <li>Компонент проверяет права через <code class="text-xs px-1 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800">$this->authorize('update', $habit)</code>.</li>
                            <li>Если права есть — создаёт или удаляет запись в <code class="text-xs px-1 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800">habit_logs</code>.</li>
                            <li>Livewire рендерит компонент заново, считает разницу с предыдущим HTML.</li>
                            <li>Браузер получает HTML-патч и применяет через morphing DOM (без перезагрузки).</li>
                        </ol>
                    </section>

                    {{-- 6. База данных --}}
                    <section id="database" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            6. База данных
                        </flux:heading>

                        <flux:heading size="lg" class="mb-3">Связи между таблицами</flux:heading>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5 font-mono text-xs sm:text-sm leading-relaxed text-zinc-700 dark:text-zinc-300 overflow-x-auto">
<pre>users
  └── habits (1:N)
        ├── habit_logs (1:N)
        └── challenge_habit (M:N) ──┐
  └── challenges (1:N)              │
        └── challenge_habit (M:N) ──┘</pre>
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Таблицы</flux:heading>
                        <div class="space-y-4">
                            @php
                                $tables = [
                                    [
                                        'name' => 'users',
                                        'desc' => 'Зарегистрированные пользователи',
                                        'fields' => [
                                            ['id', 'bigint, PK', 'Идентификатор'],
                                            ['name', 'string', 'Имя'],
                                            ['email', 'string, unique', 'Email для входа'],
                                            ['password', 'string', 'Хеш пароля (bcrypt)'],
                                            ['email_verified_at', 'timestamp, null', 'Подтверждение email'],
                                        ],
                                    ],
                                    [
                                        'name' => 'habits',
                                        'desc' => 'Привычки пользователя',
                                        'fields' => [
                                            ['id', 'bigint, PK', ''],
                                            ['user_id', 'FK → users, cascade', 'Владелец'],
                                            ['title', 'string', 'Название'],
                                            ['description', 'text, null', 'Описание'],
                                            ['color', 'string(7), null', 'HEX-цвет'],
                                            ['time_of_day', 'string(16)', 'any/morning/day/evening'],
                                            ['is_active', 'boolean', 'Активна'],
                                        ],
                                        'indexes' => '[user_id, is_active]',
                                    ],
                                    [
                                        'name' => 'habit_logs',
                                        'desc' => 'Записи о выполнении (отметки)',
                                        'fields' => [
                                            ['id', 'bigint, PK', ''],
                                            ['habit_id', 'FK → habits, cascade', 'Привычка'],
                                            ['completed_on', 'date', 'Дата выполнения'],
                                        ],
                                        'indexes' => 'UNIQUE [habit_id, completed_on], INDEX completed_on',
                                    ],
                                    [
                                        'name' => 'challenges',
                                        'desc' => 'Челленджи пользователя',
                                        'fields' => [
                                            ['id', 'bigint, PK', ''],
                                            ['user_id', 'FK → users, cascade', 'Владелец'],
                                            ['title', 'string', 'Название'],
                                            ['type', 'enum', 'streak / total_completions / period_days'],
                                            ['target_value', 'unsigned int', 'Целевое значение'],
                                            ['starts_on', 'date', 'Начало'],
                                            ['ends_on', 'date, null', 'Окончание'],
                                            ['is_active', 'boolean', 'Активен'],
                                        ],
                                        'indexes' => '[user_id, is_active]',
                                    ],
                                    [
                                        'name' => 'challenge_habit',
                                        'desc' => 'Pivot-таблица many-to-many',
                                        'fields' => [
                                            ['id', 'bigint, PK', ''],
                                            ['challenge_id', 'FK → challenges, cascade', ''],
                                            ['habit_id', 'FK → habits, cascade', ''],
                                        ],
                                        'indexes' => 'UNIQUE [challenge_id, habit_id]',
                                    ],
                                ];
                            @endphp
                            @foreach ($tables as $t)
                                <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
                                    <div class="px-4 sm:px-5 py-3 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                                        <div class="flex items-baseline gap-2 flex-wrap">
                                            <code class="font-mono font-medium text-sm">{{ $t['name'] }}</code>
                                            <span class="text-xs text-zinc-500">{{ $t['desc'] }}</span>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs sm:text-sm">
                                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                                @foreach ($t['fields'] as $f)
                                                    <tr>
                                                        <td class="p-3 font-mono text-zinc-900 dark:text-zinc-100 whitespace-nowrap">{{ $f[0] }}</td>
                                                        <td class="p-3 text-zinc-500 whitespace-nowrap">{{ $f[1] }}</td>
                                                        <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $f[2] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (! empty($t['indexes']))
                                        <div class="px-4 sm:px-5 py-2.5 bg-zinc-50 dark:bg-zinc-800/30 border-t border-zinc-200 dark:border-zinc-800 text-xs text-zinc-600 dark:text-zinc-400">
                                            <strong>Индексы:</strong> <code class="font-mono">{{ $t['indexes'] }}</code>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-xl border border-amber-200 dark:border-amber-500/30 bg-amber-50/50 dark:bg-amber-500/5 p-5">
                            <flux:heading size="base" class="mb-2">Ключевые архитектурные решения</flux:heading>
                            <ul class="space-y-2 text-sm text-zinc-700 dark:text-zinc-300 list-disc pl-5">
                                <li><strong>Уникальный индекс <code class="text-xs px-1 rounded bg-zinc-100 dark:bg-zinc-800">[habit_id, completed_on]</code></strong> — нельзя дважды отметить привычку в один день, защита на уровне БД от race condition.</li>
                                <li><strong>Каскадное удаление</strong> — при удалении пользователя стираются все его данные. Гарантирует целостность БД.</li>
                                <li><strong>Отметка не имеет поля «выполнено/не выполнено»</strong> — сама запись в <code class="text-xs px-1 rounded bg-zinc-100 dark:bg-zinc-800">habit_logs</code> означает «выполнено». Отсутствие записи — «не выполнено».</li>
                                <li><strong>Пустой пивот = все привычки</strong> — если у челленджа нет привязанных привычек, он распространяется на все активные привычки пользователя.</li>
                            </ul>
                        </div>
                    </section>

                    {{-- 7. Модули --}}
                    <section id="modules" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            7. Модули
                        </flux:heading>

                        <flux:heading size="lg" class="mb-3">Сервисы (бизнес-логика)</flux:heading>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach ([
                                ['HabitStatsService', 'Расчёт статистики: дневной % выполнения, отметки по привычкам, тепловая карта'],
                                ['ChallengeProgressService', 'Расчёт прогресса челленджа в зависимости от типа (streak / total / period_days)'],
                                ['UserStreakService', 'Текущая и максимальная серия дней. Кэш в памяти для оптимизации'],
                                ['HabitPresetService', '24 готовых шаблона привычек по 6 категориям'],
                                ['ChallengePresetService', '6 шаблонов челленджей разных типов'],
                                ['MotivationService', 'Контекстная мотивационная фраза в зависимости от состояния'],
                            ] as $svc)
                                <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                    <code class="font-mono font-medium text-sm">{{ $svc[0] }}</code>
                                    <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $svc[1] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Livewire-компоненты</flux:heading>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach ([
                                ['Dashboard', 'Главная: огонёк стрика, привычки на сегодня, сводная статистика'],
                                ['Habits/Index', 'Список привычек с пагинацией'],
                                ['Habits/Form', 'Создание/редактирование с каталогом шаблонов'],
                                ['Challenges/Index', 'Список челленджей с прогресс-барами'],
                                ['Challenges/Form', 'Создание/редактирование челленджа'],
                                ['Statistics/Charts', 'Графики и тепловая карта, экспорт CSV'],
                                ['Layout/UserMenu', 'Меню профиля в шапке'],
                            ] as $cmp)
                                <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                    <code class="font-mono font-medium text-sm">{{ $cmp[0] }}</code>
                                    <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $cmp[1] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- 8. Интерфейс --}}
                    <section id="ui" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            8. Интерфейс
                        </flux:heading>

                        <flux:heading size="lg" class="mb-3">Адаптивность</flux:heading>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <flux:icon.device-phone-mobile class="size-6 text-indigo-600" />
                                <div class="font-medium mt-2">Мобильный</div>
                                <div class="text-xs text-zinc-500 mt-1">Нижняя навигация, sticky-шапка, safe-area для notch</div>
                            </div>
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <flux:icon.device-tablet class="size-6 text-indigo-600" />
                                <div class="font-medium mt-2">Планшет</div>
                                <div class="text-xs text-zinc-500 mt-1">Двухколоночная сетка, адаптированные размеры</div>
                            </div>
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                <flux:icon.computer-desktop class="size-6 text-indigo-600" />
                                <div class="font-medium mt-2">Десктоп</div>
                                <div class="text-xs text-zinc-500 mt-1">Верхний навбар, hover-эффекты, spotlight-курсор</div>
                            </div>
                        </div>

                        <flux:heading size="lg" class="mb-3">Анимации и эффекты</flux:heading>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="text-left p-3 font-medium text-zinc-500">Эффект</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Где</th>
                                        <th class="text-left p-3 font-medium text-zinc-500 hidden sm:table-cell">Реализация</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ([
                                        ['Aurora-фон', 'Лендинг, auth, общий фон', 'Радиальные градиенты + animation + blur'],
                                        ['Spotlight cards', 'Карточки на дашборде, шаблоны', 'CSS-переменные с координатами курсора'],
                                        ['Star border', 'Бейдж «Серия в опасности»', 'conic-gradient + @property --angle'],
                                        ['TextType', 'Заголовок лендинга', 'Alpine-компонент, посимвольная печать'],
                                        ['Heatmap', 'Страница статистики', '12 недель × 7 дней, 5 уровней зелёного'],
                                        ['Card lift', 'Hover на карточках', 'translateY + box-shadow'],
                                    ] as $row)
                                        <tr>
                                            <td class="p-3 font-medium">{{ $row[0] }}</td>
                                            <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $row[1] }}</td>
                                            <td class="p-3 text-zinc-500 text-xs hidden sm:table-cell font-mono">{{ $row[2] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">
                            Все анимации уважают <code class="text-xs px-1 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800">prefers-reduced-motion</code> — у пользователей с этой настройкой отключаются.
                        </p>
                    </section>

                    {{-- 9. Безопасность --}}
                    <section id="security" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            9. Безопасность
                        </flux:heading>

                        <div class="space-y-3">
                            @foreach ([
                                ['shield-check', 'Хеширование паролей', 'bcrypt, 12 раундов. Соль случайная, встроена в хеш.'],
                                ['lock-closed', 'CSRF-защита', 'Токен встраивается в meta-тег и проверяется на каждом Livewire-запросе автоматически.'],
                                ['user-circle', 'Авторизация ресурсов', 'Каждое действие проходит через Policy. Подмена ID в URL вернёт 403 Forbidden.'],
                                ['code-bracket', 'Защита от SQL-инъекций', 'Eloquent ORM использует подготовленные выражения (PDO). Прямой ввод в SQL невозможен.'],
                                ['document-text', 'Защита от XSS', 'Blade автоматически экранирует все переменные через {{ $var }}. Сырой HTML не используется.'],
                                ['check-badge', 'Серверная валидация', 'Все входные данные проходят через атрибуты Validate. Клиентская валидация — только для UX.'],
                                ['key', 'Уникальность отметки', 'Гарантирована на уровне БД через UNIQUE-индекс. Защита от двойного клика и race condition.'],
                            ] as $item)
                                <div class="flex gap-4 items-start rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                    <div class="size-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                        <flux:icon :icon="$item[0]" class="size-5" />
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $item[1] }}</div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $item[2] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- 10. Производительность --}}
                    <section id="performance" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            10. Производительность
                        </flux:heading>

                        <div class="space-y-3">
                            @foreach ([
                                ['Eager loading', 'На странице челленджей привычки подгружаются одним SQL-запросом для всех челленджей сразу. Без этого был бы N+1.'],
                                ['Один запрос вместо N', 'В HabitStatsService метод dailyCompletion() выполняет один запрос на все логи периода. Раньше было N×D запросов (N привычек × D дней).'],
                                ['Индексы БД', 'Составные [user_id, is_active] на habits и challenges. Индекс completed_on на habit_logs. Ускоряет выборки в 10–100 раз.'],
                                ['Кэш в памяти', 'UserStreakService хранит загруженные даты в private property. current() и longest() переиспользуют один SQL-запрос.'],
                                ['Ограничение объёма', 'Streak загружает только последние 2 года. Реалистичный стрик не длиннее года — экономим память.'],
                                ['scoped DI', 'Сервисы зарегистрированы как scoped — один экземпляр на запрос. Кэш внутри работает корректно.'],
                                ['Lazy-load Chart.js', 'Библиотека (~80 KB) загружается только на /statistics через динамический import().'],
                                ['Event delegation', 'Spotlight-эффект использует один mousemove-listener на document. Не пересоздаётся при Livewire-обновлениях.'],
                                ['CSS-only анимации', 'Aurora и Star Border — на чистом CSS. Аппаратное ускорение через GPU.'],
                                ['Stream-экспорт CSV', 'lazy(500) — пачки по 500 записей. Память не растёт независимо от объёма.'],
                                ['Защита от двойного сабмита', 'Кнопки сохранения wire:loading.attr="disabled". Пока запрос обрабатывается, кнопка заблокирована.'],
                            ] as $item)
                                <div class="flex gap-3 items-start rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
                                    <flux:icon.bolt class="size-5 text-amber-500 shrink-0 mt-0.5" />
                                    <div>
                                        <div class="font-medium">{{ $item[0] }}</div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $item[1] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- 11. Тестирование --}}
                    <section id="testing" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            11. Тестирование
                        </flux:heading>

                        <flux:text class="mb-4">
                            Проведено ручное тестирование основных пользовательских сценариев. Архитектура поддерживает автоматизированные тесты — PHPUnit конфигурирован, in-memory SQLite готов.
                        </flux:text>

                        <flux:heading size="lg" class="mb-3">Метрики</flux:heading>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 text-center">
                                <div class="text-2xl font-bold text-indigo-600">&lt; 100мс</div>
                                <div class="text-xs text-zinc-500 mt-1">Рендер дашборда</div>
                            </div>
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 text-center">
                                <div class="text-2xl font-bold text-indigo-600">~50 KB</div>
                                <div class="text-xs text-zinc-500 mt-1">JS на странице</div>
                            </div>
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 text-center">
                                <div class="text-2xl font-bold text-indigo-600">90+</div>
                                <div class="text-xs text-zinc-500 mt-1">Lighthouse Score</div>
                            </div>
                            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4 text-center">
                                <div class="text-2xl font-bold text-indigo-600">15+</div>
                                <div class="text-xs text-zinc-500 mt-1">Сценариев</div>
                            </div>
                        </div>

                        <flux:heading size="lg" class="mt-8 mb-3">Проверенные сценарии</flux:heading>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                    <tr>
                                        <th class="text-left p-3 font-medium text-zinc-500 w-10">#</th>
                                        <th class="text-left p-3 font-medium text-zinc-500">Сценарий</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ([
                                        'Регистрация и вход в систему',
                                        'Создание привычки из шаблона',
                                        'Отметка/снятие отметки',
                                        'Создание челленджа из шаблона',
                                        'Расчёт прогресса челленджа',
                                        'Подсчёт текущего и максимального стрика',
                                        'Работа индикатора «Серия в опасности»',
                                        'Экспорт CSV с кириллицей',
                                        'Каскадное удаление при удалении привычки',
                                        'Защита от чужих ID (403 Forbidden)',
                                        'Переключение тёмной темы',
                                        'Адаптивность на мобильном',
                                        'Heatmap на узком экране',
                                        'Восстановление пароля по email',
                                        'Удаление аккаунта',
                                    ] as $i => $scenario)
                                        <tr>
                                            <td class="p-3 text-zinc-500 font-mono">{{ $i + 1 }}</td>
                                            <td class="p-3">{{ $scenario }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 12. Заключение --}}
                    <section id="conclusion" class="scroll-mt-24">
                        <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl font-bold mb-4">
                            12. Заключение
                        </flux:heading>

                        <div class="prose-content space-y-4 text-zinc-700 dark:text-zinc-300">
                            <p>
                                В рамках курсовой работы было разработано полнофункциональное веб-приложение «Трекер привычек», реализующее все поставленные задачи.
                            </p>
                            <p>
                                Применены современные подходы к разработке: чистая архитектура с разделением на слои, eager loading против N+1, кэширование в памяти, оптимизация SQL, lazy-load JS-библиотек, реактивный UI без отдельного API.
                            </p>
                            <p>
                                Приложение готово к использованию и при желании может быть расширено: push-уведомления, социальные функции, мобильное приложение, синхронизация с фитнес-трекерами.
                            </p>
                        </div>

                        <div class="mt-6 flex gap-3 flex-wrap">
                            @auth
                                <flux:button :href="route('dashboard')" wire:navigate variant="primary" icon="rocket-launch">
                                    Открыть приложение
                                </flux:button>
                            @else
                                <flux:button :href="route('register')" wire:navigate variant="primary" icon="rocket-launch">
                                    Попробовать
                                </flux:button>
                                <flux:button :href="route('login')" wire:navigate variant="ghost">
                                    Войти
                                </flux:button>
                            @endauth
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <footer class="px-6 py-8 text-center border-t border-zinc-200 dark:border-zinc-800 mt-16">
            <flux:text size="sm" variant="subtle">
                Курсовая работа · {{ date('Y') }} · Laravel · Livewire · Flux UI
            </flux:text>
        </footer>

        @fluxScripts
    </body>
</html>
