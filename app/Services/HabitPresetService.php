<?php

namespace App\Services;

use App\Models\Habit;

class HabitPresetService
{
    /**
     * @return array<string, array{name: string, icon: string}>
     */
    public function categories(): array
    {
        return [
            'health' => ['name' => 'Здоровье', 'icon' => 'heart'],
            'fitness' => ['name' => 'Спорт', 'icon' => 'bolt'],
            'productivity' => ['name' => 'Продуктивность', 'icon' => 'sparkles'],
            'learning' => ['name' => 'Учёба', 'icon' => 'book-open'],
            'nutrition' => ['name' => 'Питание', 'icon' => 'cake'],
            'mindfulness' => ['name' => 'Осознанность', 'icon' => 'sun'],
        ];
    }

    /**
     * @return array<int, array{title: string, description: ?string, color: string, category: string, time_of_day: string}>
     */
    public function all(): array
    {
        return [
            // Здоровье
            ['title' => 'Пить 8 стаканов воды', 'description' => 'Норма жидкости — около 2 литров в день.', 'color' => '#06b6d4', 'category' => 'health', 'time_of_day' => Habit::TIME_ANY],
            ['title' => '10 000 шагов', 'description' => 'Ежедневная активность — основа здоровья.', 'color' => '#10b981', 'category' => 'health', 'time_of_day' => Habit::TIME_DAY],
            ['title' => 'Спать 8 часов', 'description' => 'Без полноценного сна не будет ни сил, ни концентрации.', 'color' => '#6366f1', 'category' => 'health', 'time_of_day' => Habit::TIME_EVENING],
            ['title' => 'Просыпаться до 7:30', 'description' => 'Раннее утро — самое продуктивное время.', 'color' => '#f59e0b', 'category' => 'health', 'time_of_day' => Habit::TIME_MORNING],

            // Спорт
            ['title' => 'Зарядка 15 минут', 'description' => 'Простая разминка с утра.', 'color' => '#ef4444', 'category' => 'fitness', 'time_of_day' => Habit::TIME_MORNING],
            ['title' => 'Пробежка', 'description' => 'Бег 20-30 минут.', 'color' => '#f97316', 'category' => 'fitness', 'time_of_day' => Habit::TIME_MORNING],
            ['title' => '50 отжиманий', 'description' => 'Можно разбить на подходы.', 'color' => '#dc2626', 'category' => 'fitness', 'time_of_day' => Habit::TIME_ANY],
            ['title' => 'Тренировка в зале', 'description' => 'Силовая или кардио.', 'color' => '#b91c1c', 'category' => 'fitness', 'time_of_day' => Habit::TIME_EVENING],
            ['title' => 'Йога', 'description' => '20 минут растяжки.', 'color' => '#a855f7', 'category' => 'fitness', 'time_of_day' => Habit::TIME_MORNING],

            // Продуктивность
            ['title' => 'Без соцсетей до обеда', 'description' => 'Не открывать ленты до 12:00.', 'color' => '#8b5cf6', 'category' => 'productivity', 'time_of_day' => Habit::TIME_MORNING],
            ['title' => 'Сделать 3 главные задачи', 'description' => 'Закрыть три приоритета дня.', 'color' => '#7c3aed', 'category' => 'productivity', 'time_of_day' => Habit::TIME_DAY],
            ['title' => 'Pomodoro 4 цикла', 'description' => '4×25 минут глубокой работы.', 'color' => '#6d28d9', 'category' => 'productivity', 'time_of_day' => Habit::TIME_DAY],
            ['title' => 'Inbox zero', 'description' => 'Разобрать почту до конца дня.', 'color' => '#5b21b6', 'category' => 'productivity', 'time_of_day' => Habit::TIME_EVENING],

            // Учёба
            ['title' => 'Читать 30 минут', 'description' => 'Любая книга — главное регулярно.', 'color' => '#0ea5e9', 'category' => 'learning', 'time_of_day' => Habit::TIME_EVENING],
            ['title' => 'Учить английский', 'description' => '15-20 минут в день.', 'color' => '#0284c7', 'category' => 'learning', 'time_of_day' => Habit::TIME_ANY],
            ['title' => 'Онлайн-курс 1 час', 'description' => 'Прохождение модуля курса.', 'color' => '#0369a1', 'category' => 'learning', 'time_of_day' => Habit::TIME_EVENING],
            ['title' => 'Решить задачу LeetCode', 'description' => 'Одна задача в день.', 'color' => '#075985', 'category' => 'learning', 'time_of_day' => Habit::TIME_ANY],

            // Питание
            ['title' => 'Без сахара', 'description' => 'Ни сладкого, ни газировки.', 'color' => '#84cc16', 'category' => 'nutrition', 'time_of_day' => Habit::TIME_ANY],
            ['title' => '5 порций овощей/фруктов', 'description' => 'Минимум 5 порций в день.', 'color' => '#65a30d', 'category' => 'nutrition', 'time_of_day' => Habit::TIME_ANY],
            ['title' => 'Полноценный завтрак', 'description' => 'Не пропускать утренний приём пищи.', 'color' => '#4d7c0f', 'category' => 'nutrition', 'time_of_day' => Habit::TIME_MORNING],
            ['title' => 'Без фастфуда', 'description' => 'Готовить дома или есть здоровую еду.', 'color' => '#3f6212', 'category' => 'nutrition', 'time_of_day' => Habit::TIME_ANY],

            // Осознанность
            ['title' => 'Медитация 10 минут', 'description' => 'Утром или перед сном.', 'color' => '#ec4899', 'category' => 'mindfulness', 'time_of_day' => Habit::TIME_MORNING],
            ['title' => 'Дневник благодарности', 'description' => 'Записать 3 вещи, за которые благодарен.', 'color' => '#db2777', 'category' => 'mindfulness', 'time_of_day' => Habit::TIME_EVENING],
            ['title' => 'Прогулка без телефона', 'description' => 'Минимум 15 минут.', 'color' => '#be185d', 'category' => 'mindfulness', 'time_of_day' => Habit::TIME_DAY],
            ['title' => 'Не отвлекаться 1 час', 'description' => 'Полная концентрация на задаче.', 'color' => '#9d174d', 'category' => 'mindfulness', 'time_of_day' => Habit::TIME_DAY],
        ];
    }

    /**
     * @return array<string, array<int, array>>
     */
    public function grouped(): array
    {
        $grouped = [];
        foreach ($this->all() as $i => $preset) {
            $grouped[$preset['category']][] = ['index' => $i] + $preset;
        }

        return $grouped;
    }

    public function get(int $index): ?array
    {
        return $this->all()[$index] ?? null;
    }
}
