<?php

namespace App\Providers;

use App\Services\ChallengePresetService;
use App\Services\HabitPresetService;
use App\Services\HabitStatsService;
use App\Services\MotivationService;
use App\Services\UserStreakService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton'ы на время запроса — кэш внутри сервисов работает корректно,
        // а DI-резолв происходит один раз.
        $this->app->scoped(UserStreakService::class);
        $this->app->scoped(HabitStatsService::class);
        $this->app->scoped(MotivationService::class);
        $this->app->scoped(HabitPresetService::class);
        $this->app->scoped(ChallengePresetService::class);
    }

    public function boot(): void
    {
        //
    }
}
