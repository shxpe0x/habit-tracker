<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Services\ChallengeProgressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public function delete(int $challengeId): void
    {
        $challenge = Challenge::where('user_id', auth()->id())->findOrFail($challengeId);
        $this->authorize('delete', $challenge);
        $challenge->delete();

        session()->flash('status', 'Челлендж удалён.');
    }

    public function render(ChallengeProgressService $progressService)
    {
        $this->authorize('viewAny', Challenge::class);

        $challenges = auth()->user()->challenges()
            ->with([
                'habits' => fn ($q) => $q->where('is_active', true),
                'user:id', // нужен в resolveHabits если habits пусто
            ])
            ->latest()
            ->paginate(10);

        $progress = [];
        foreach ($challenges as $challenge) {
            $progress[$challenge->id] = $progressService->calculate($challenge);
        }

        return view('livewire.challenges.index', [
            'challenges' => $challenges,
            'progress' => $progress,
        ]);
    }
}
