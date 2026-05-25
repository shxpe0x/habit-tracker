# Habit Tracker

Веб-приложение для отслеживания ежедневных привычек. Курсовая работа.

Каждый пользователь ведёт свой список привычек, отмечает их выполнение, ставит себе челленджи и смотрит статистику в графиках. Интерфейс на русском.

## Что умеет

- Регистрация, вход, восстановление пароля, верификация e-mail
- CRUD привычек: название, описание, цвет, время дня (утро / день / вечер / любое)
- Отметка выполнения за сегодня прямо с дашборда (один клик)
- Челленджи трёх типов:
  - `streak` — серия дней подряд
  - `total_completions` — суммарное число отметок
  - `period_days` — количество дней с отметками за период
- Челлендж можно привязать к конкретным привычкам или ко всем сразу
- Статистика: дневной % выполнения, разбивка по привычкам, heatmap активности за 12 недель
- Экспорт статистики в CSV
- Стрик в стиле Duolingo: текущая и максимальная серия, индикатор «опасности» если сегодня пусто
- Пресеты привычек (24 шаблона) и челленджей (6 шаблонов) — чтобы не начинать с нуля
- Тёмная тема, переключается из меню профиля

## Стек

- PHP 8.3, Laravel 13
- Livewire 3 + Volt (auth-страницы) + Flux UI 2
- Tailwind CSS 4 (без `tailwind.config.js`, всё в `resources/css/app.css`)
- Alpine.js (идёт с Livewire)
- Chart.js 4 для графиков
- Vite 8 для сборки
- SQLite по умолчанию, можно переключить на MySQL/MariaDB через `.env`

## Запуск

Нужен PHP 8.3+, Composer и Node.js 20+.

```bash
git clone https://github.com/shxpe0x/habit-tracker.git
cd habit-tracker

composer install
npm install

cp .env.example .env
php artisan key:generate

# создать пустой файл для SQLite и накатить миграции
type nul > database/database.sqlite   # на Windows
# touch database/database.sqlite      # на Linux/Mac
php artisan migrate

npm run build
```

Дальше есть два варианта.

**С Laravel Herd** (рекомендуется на Windows). Сайт сразу будет доступен по `http://kurs.test`, ничего больше делать не нужно. Если хочется hot-reload фронта — параллельно запустить:

```bash
npm run dev
```

**Без Herd**, через встроенный сервер PHP:

```bash
php -S 127.0.0.1:8888 -t public
```

В этом случае в `.env` пропишите `APP_URL=http://127.0.0.1:8888`, иначе Vite не подхватит ассеты.

## Структура

```
app/
├── Livewire/          # full-page компоненты (Dashboard, Habits, Challenges, Statistics)
├── Models/            # User, Habit, HabitLog, Challenge
├── Policies/          # проверка владельца ресурса
├── Services/          # бизнес-логика: статистика, прогресс челленджей, стрик, пресеты
└── Http/Controllers/  # только StatisticsExportController и Auth/VerifyEmailController

resources/
├── css/app.css        # Tailwind v4 + Flux + @theme
├── js/app.js          # window.Chart = Chart.js
└── views/livewire/    # Blade-шаблоны компонентов

database/migrations/   # users, habits, habit_logs, challenges, challenge_habit
routes/web.php         # все маршруты ведут напрямую на Livewire-классы
```

Бизнес-логика вынесена в сервисы, чтобы Livewire-компоненты оставались тонкими:

- `HabitStatsService` — дневные проценты, breakdown по привычкам, сводка
- `ChallengeProgressService` — прогресс челленджа в зависимости от его типа
- `UserStreakService` — текущий и максимальный стрик пользователя
- `HabitPresetService`, `ChallengePresetService` — шаблоны
- `MotivationService` — подбирает фразу под контекст (стрик / прогресс дня)

UI собран на Flux UI: `<flux:button>`, `<flux:input>`, `<flux:table>`, `<flux:callout>` и т.д. Своих Blade-компонентов почти нет.

## Команды

```bash
php artisan migrate           # миграции
php artisan migrate:fresh     # пересоздать БД с нуля
composer test                 # PHPUnit
./vendor/bin/pint             # форматирование PSR-12
npm run dev                   # Vite в dev-режиме
npm run build                 # production-сборка
```

## Замечания

- На Windows скрипт `composer dev` не работает: `pail` требует расширение `pcntl`, которого в винде нет. Поэтому либо Herd, либо запускайте `php -S` и `npm run dev` в разных терминалах вручную.
- Tailwind v4 настраивается через CSS, файла `tailwind.config.js` нет — это не баг.
- SQLite-файл по умолчанию лежит в `database/database.sqlite` и в репозиторий не коммитится.
