<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use App\Services\ChallengeProgressService;
use App\Services\HabitStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HabitTrackerSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_habit_tracker_flow(): void
    {
        $user = User::factory()->create();

        $habit = Habit::create([
            'user_id' => $user->id,
            'title' => 'Чтение',
            'color' => '#6366f1',
            'is_active' => true,
        ]);

        HabitLog::create([
            'habit_id' => $habit->id,
            'completed_on' => today(),
        ]);

        $challenge = Challenge::create([
            'user_id' => $user->id,
            'title' => '7 дней',
            'type' => 'streak',
            'target_value' => 7,
            'starts_on' => today()->subDays(6),
            'is_active' => true,
        ]);
        $challenge->habits()->attach($habit->id);

        $progress = app(ChallengeProgressService::class)->calculate($challenge);
        $this->assertGreaterThanOrEqual(1, $progress['current']);

        $stats = app(HabitStatsService::class);
        $daily = $stats->dailyCompletion($user, 7);
        $this->assertNotEmpty($daily['labels']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('habits.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('challenges.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('statistics'))
            ->assertOk();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Dashboard::class)
            ->call('toggleToday', $habit->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('habit_logs', [
            'habit_id' => $habit->id,
            'completed_on' => today()->toDateString(),
        ]);
    }
}
