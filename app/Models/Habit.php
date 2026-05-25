<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    public const DEFAULT_COLOR = '#6366f1';

    public const TIME_ANY = 'any';
    public const TIME_MORNING = 'morning';
    public const TIME_DAY = 'day';
    public const TIME_EVENING = 'evening';

    public const TIME_LABELS = [
        self::TIME_ANY => 'Любое время',
        self::TIME_MORNING => 'Утро',
        self::TIME_DAY => 'День',
        self::TIME_EVENING => 'Вечер',
    ];

    public const TIME_ICONS = [
        self::TIME_ANY => null,
        self::TIME_MORNING => '☀️',
        self::TIME_DAY => '🌤️',
        self::TIME_EVENING => '🌙',
    ];

    /**
     * Сортировочный вес для текущего часа — чтобы актуальные привычки шли первыми.
     */
    public static function timeWeight(string $timeOfDay, int $currentHour): int
    {
        $current = match (true) {
            $currentHour < 12 => self::TIME_MORNING,
            $currentHour < 18 => self::TIME_DAY,
            default => self::TIME_EVENING,
        };

        if ($timeOfDay === $current) return 0;
        if ($timeOfDay === self::TIME_ANY) return 1;
        return 2;
    }

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'color',
        'time_of_day',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected function color(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? self::DEFAULT_COLOR,
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function challenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class);
    }

    public function isCompletedOn(string $date): bool
    {
        return $this->logs()->whereDate('completed_on', $date)->exists();
    }
}
