<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public function delete(int $habitId): void
    {
        $habit = Habit::where('user_id', auth()->id())->findOrFail($habitId);
        $this->authorize('delete', $habit);
        $habit->delete();

        session()->flash('status', 'Привычка удалена.');
    }

    public function render()
    {
        $this->authorize('viewAny', Habit::class);

        return view('livewire.habits.index', [
            'habits' => auth()->user()->habits()->latest()->paginate(10),
        ]);
    }
}
