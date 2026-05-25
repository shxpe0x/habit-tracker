<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenge extends Model
{
    public const TYPE_STREAK = 'streak';

    public const TYPE_TOTAL_COMPLETIONS = 'total_completions';

    public const TYPE_PERIOD_DAYS = 'period_days';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'target_value',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_active' => 'boolean',
            'target_value' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habits(): BelongsToMany
    {
        return $this->belongsToMany(Habit::class);
    }

    public function appliesToAllHabits(): bool
    {
        // Если habits загружены через with() — используем relationLoaded для оптимизации
        if ($this->relationLoaded('habits')) {
            return $this->habits->isEmpty();
        }

        return $this->habits()->count() === 0;
    }
}
