<h1 align="center">Habit Tracker</h1>

<p align="center">
  Веб-приложение для отслеживания привычек: ставишь цели, отмечаешь выполнение, смотришь графики и идёшь по сериям. Курсовая работа.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 3">
  <img src="https://img.shields.io/badge/Tailwind-4-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind 4">
  <img src="https://img.shields.io/badge/Chart.js-4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js">
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/license-MIT-22c55e?style=for-the-badge" alt="License">
</p>

<p align="center">
  <a href="#-возможности">Возможности</a> ·
  <a href="#-стек">Стек</a> ·
  <a href="#-запуск">Запуск</a> ·
  <a href="#-структура">Структура</a> ·
  <a href="#-команды">Команды</a> ·
  <a href="#-замечания">Замечания</a>
</p>

---

## ✨ Возможности

| | |
|---|---|
| 🔐 **Аутентификация** | регистрация, вход, восстановление пароля, верификация e-mail (Breeze + Volt) |
| 📋 **Привычки** | CRUD, цвет, время дня (утро / день / вечер / любое) |
| ✅ **Отметка за день** | один клик прямо с дашборда, привычки группируются по времени дня |
| 🎯 **Челленджи** | три типа: серия дней (`streak`), всего отметок (`total_completions`), дней с отметками за период (`period_days`) |
| 🔗 **Привязка** | челлендж можно повесить на конкретные привычки или на все сразу |
| 📊 **Статистика** | дневной % выполнения, разбивка по привычкам, heatmap активности за 12 недель |
| 📥 **Экспорт** | выгрузка статистики в CSV |
| 🔥 **Стрик** | в стиле Duolingo — текущая и максимальная серия, индикатор «опасности» если сегодня пусто |
| 🧩 **Пресеты** | 24 готовые привычки и 6 челленджей, чтобы не начинать с нуля |
| 🌙 **Тёмная тема** | переключается из меню профиля |

Интерфейс полностью на русском.

## 🛠 Стек

**Backend** — PHP 8.3, Laravel 13, Livewire 3 + Volt, Flux UI 2
**Frontend** — Tailwind CSS 4 (без `tailwind.config.js`, всё через `@theme` в CSS), Alpine.js, Chart.js 4, Vite 8
**База** — SQLite по умолчанию, можно переключить на MySQL/MariaDB через `.env`
**Тулинг** — Laravel Pint (PSR-12), PHPUnit 12

## 🚀 Запуск

Нужен PHP 8.3+, Composer и Node.js 20+.

```bash
git clone https://github.com/shxpe0x/habit-tracker.git
cd habit-tracker

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Создать пустой файл базы и накатить миграции:

```bash
# Windows
type nul > database/database.sqlite

# Linux / macOS
touch database/database.sqlite

php artisan migrate
npm run build
```

Дальше есть два варианта.

<details>
<summary><b>С Laravel Herd</b> (рекомендуется на Windows)</summary>

Сайт сразу будет доступен по `http://kurs.test`, ничего больше делать не нужно. Если хочется hot-reload фронта — параллельно запустить:

```bash
npm run dev
```
</details>

<details>
<summary><b>Без Herd</b>, через встроенный сервер PHP</summary>

```bash
php -S 127.0.0.1:8888 -t public
```

В этом случае в `.env` пропишите `APP_URL=http://127.0.0.1:8888`, иначе Vite не подхватит ассеты.
</details>

## 📂 Структура

```
app/
├── Livewire/          # full-page компоненты (Dashboard, Habits, Challenges, Statistics)
├── Models/            # User, Habit, HabitLog, Challenge
├── Policies/          # проверка владельца ресурса
├── Services/          # бизнес-логика: статистика, прогресс, стрик, пресеты
└── Http/Controllers/  # только StatisticsExportController и Auth/VerifyEmailController

resources/
├── css/app.css        # Tailwind v4 + Flux + @theme
├── js/app.js          # window.Chart = Chart.js
└── views/livewire/    # Blade-шаблоны компонентов

database/migrations/   # users, habits, habit_logs, challenges, challenge_habit
routes/web.php         # все маршруты ведут напрямую на Livewire-классы
```

Бизнес-логика вынесена в сервисы, чтобы Livewire-компоненты оставались тонкими:

| Сервис | За что отвечает |
|---|---|
| `HabitStatsService` | дневные проценты, breakdown по привычкам, сводка |
| `ChallengeProgressService` | прогресс челленджа в зависимости от его типа |
| `UserStreakService` | текущий и максимальный стрик пользователя |
| `HabitPresetService` / `ChallengePresetService` | шаблоны |
| `MotivationService` | подбирает фразу под контекст (стрик / прогресс дня) |

UI собран на Flux UI: `<flux:button>`, `<flux:input>`, `<flux:table>`, `<flux:callout>` и т.д. Своих Blade-компонентов почти нет.

## 🧰 Команды

```bash
php artisan migrate           # миграции
php artisan migrate:fresh     # пересоздать БД с нуля
composer test                 # PHPUnit
./vendor/bin/pint             # форматирование PSR-12
npm run dev                   # Vite в dev-режиме
npm run build                 # production-сборка
```

## 📝 Замечания

- На Windows скрипт `composer dev` не работает — `pail` требует расширение `pcntl`, которого в винде нет. Поэтому либо Herd, либо `php -S` и `npm run dev` в разных терминалах вручную.
- Tailwind v4 настраивается через CSS, файла `tailwind.config.js` нет — это не баг.
- SQLite-файл по умолчанию лежит в `database/database.sqlite` и в репозиторий не коммитится.

## 📄 Лицензия

MIT
