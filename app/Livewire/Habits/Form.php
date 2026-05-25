<?php

namespace App\Livewire\Habits;

use App\Models\Habit;
use App\Services\HabitPresetService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Form extends Component
{
    use AuthorizesRequests;

    public ?Habit $habit = null;

    #[Validate('required|string|min:2|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('nullable|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public ?string $color = Habit::DEFAULT_COLOR;

    #[Validate('required|in:any,morning,day,evening')]
    public string $time_of_day = Habit::TIME_ANY;

    public bool $is_active = true;

    public string $activeCategory = 'health';

    public bool $showAdvanced = false;

    public function mount(?Habit $habit = null): void
    {
        if ($habit?->exists) {
            $this->authorize('update', $habit);
            $this->habit = $habit;
            $this->title = $habit->title;
            $this->description = $habit->description;
            $this->color = $habit->color;
            $this->time_of_day = $habit->time_of_day ?? Habit::TIME_ANY;
            $this->is_active = $habit->is_active;
            $this->showAdvanced = true;
        } else {
            $this->authorize('create', Habit::class);
        }
    }

    public function applyPreset(int $index, HabitPresetService $presets): void
    {
        $preset = $presets->get($index);
        if (! $preset) {
            return;
        }

        $this->title = $preset['title'];
        $this->description = $preset['description'];
        $this->color = $preset['color'];
        $this->time_of_day = $preset['time_of_day'] ?? Habit::TIME_ANY;
    }

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->habit) {
            $this->authorize('update', $this->habit);
            $this->habit->update($validated);
            session()->flash('status', 'Привычка обновлена.');
        } else {
            auth()->user()->habits()->create($validated);
            session()->flash('status', 'Привычка создана.');
        }

        $this->redirect(route('habits.index'), navigate: true);
    }

    public function render(HabitPresetService $presets)
    {
        return view('livewire.habits.form', [
            'categories' => $presets->categories(),
            'presetsByCategory' => $presets->grouped(),
        ]);
    }
}
