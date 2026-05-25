<?php

namespace App\Services;

use App\Models\Challenge;

class ChallengePresetService
{
    /**
     * @return array<int, array{title: string, description: string, type: string, target_value: int, days: int}>
     */
    public function all(): array
    {
        return [
            [
                'title' => '7 дней подряд',
                'description' => 'Отмечай хотя бы одну привычку 7 дней подряд.',
                'type' => Challenge::TYPE_STREAK,
                'target_value' => 7,
                'days' => 7,
            ],
            [
                'title' => '30 дней привычки',
                'description' => 'Серия из 30 дней без пропусков.',
                'type' => Challenge::TYPE_STREAK,
                'target_value' => 30,
                'days' => 30,
            ],
            [
                'title' => 'Месяц регулярности',
                'description' => '20 активных дней за месяц.',
                'type' => Challenge::TYPE_PERIOD_DAYS,
                'target_value' => 20,
                'days' => 30,
            ],
            [
                'title' => '100 отметок',
                'description' => 'Накопи 100 отметок выполнения.',
                'type' => Challenge::TYPE_TOTAL_COMPLETIONS,
                'target_value' => 100,
                'days' => 60,
            ],
            [
                'title' => '21 день — формирование привычки',
                'description' => 'Считается, что 21 день закрепляет привычку.',
                'type' => Challenge::TYPE_STREAK,
                'target_value' => 21,
                'days' => 21,
            ],
            [
                'title' => '50 отметок за месяц',
                'description' => 'Минимум 50 отметок за 30 дней.',
                'type' => Challenge::TYPE_TOTAL_COMPLETIONS,
                'target_value' => 50,
                'days' => 30,
            ],
        ];
    }

    public function get(int $index): ?array
    {
        return $this->all()[$index] ?? null;
    }
}
