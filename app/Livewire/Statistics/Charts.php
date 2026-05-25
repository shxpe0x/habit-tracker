<?php

namespace App\Livewire\Statistics;

use App\Services\HabitStatsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Charts extends Component
{
    public int $period = 7;

    public function updatedPeriod(): void
    {
        $stats = app(HabitStatsService::class);
        $user = auth()->user();

        $this->dispatch('charts-updated',
            daily: $stats->dailyCompletion($user, $this->period),
            habits: $stats->perHabitBreakdown($user, $this->period),
        );
    }

    public function exportCsv()
    {
        return redirect()->route('statistics.export');
    }

    public function render(HabitStatsService $stats)
    {
        $user = auth()->user();

        return view('livewire.statistics.charts', [
            'dailyChart' => $stats->dailyCompletion($user, $this->period),
            'habitChart' => $stats->perHabitBreakdown($user, $this->period),
            'heatmap' => $stats->heatmap($user, 12),
        ]);
    }
}
