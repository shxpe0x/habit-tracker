<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Services\ChallengePresetService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Form extends Component
{
    use AuthorizesRequests;

    public ?Challenge $challenge = null;

    #[Validate('required|string|min:2|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('required|in:streak,total_completions,period_days')]
    public string $type = 'streak';

    #[Validate('required|integer|min:1|max:365')]
    public int $target_value = 7;

    #[Validate('required|date')]
    public string $starts_on = '';

    #[Validate('nullable|date|after_or_equal:starts_on')]
    public ?string $ends_on = null;

    public bool $is_active = true;

    public array $selectedHabits = [];

    public bool $showAdvanced = false;

    public function mount(?Challenge $challenge = null): void
    {
        $this->starts_on = now()->toDateString();

        if ($challenge?->exists) {
            $this->authorize('update', $challenge);
            $this->challenge = $challenge;
            $this->title = $challenge->title;
            $this->description = $challenge->description;
            $this->type = $challenge->type;
            $this->target_value = $challenge->target_value;
            $this->starts_on = $challenge->starts_on->toDateString();
            $this->ends_on = $challenge->ends_on?->toDateString();
            $this->is_active = $challenge->is_active;
            $this->selectedHabits = $challenge->habits()->pluck('habits.id')->all();
            $this->showAdvanced = true;
        } else {
            $this->authorize('create', Challenge::class);
        }
    }

    public function applyPreset(int $index, ChallengePresetService $presets): void
    {
        $preset = $presets->get($index);
        if (! $preset) {
            return;
        }

        $this->title = $preset['title'];
        $this->description = $preset['description'];
        $this->type = $preset['type'];
        $this->target_value = $preset['target_value'];
        $this->starts_on = now()->toDateString();
        $this->ends_on = now()->addDays($preset['days'])->toDateString();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => ['required', Rule::in(['streak', 'total_completions', 'period_days'])],
            'target_value' => 'required|integer|min:1|max:365',
            'starts_on' => 'required|date',
            'ends_on' => 'nullable|date|after_or_equal:starts_on',
            'selectedHabits' => 'array',
            'selectedHabits.*' => [
                Rule::exists('habits', 'id')->where(fn ($q) => $q->where('user_id', auth()->id())),
            ],
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'target_value' => $this->target_value,
            'starts_on' => $this->starts_on,
            'ends_on' => $this->ends_on ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->challenge) {
            $this->authorize('update', $this->challenge);
            $this->challenge->update($data);
            $challenge = $this->challenge;
            session()->flash('status', 'Челлендж обновлён.');
        } else {
            $challenge = auth()->user()->challenges()->create($data);
            session()->flash('status', 'Челлендж создан.');
        }

        $challenge->habits()->sync($this->selectedHabits);

        $this->redirect(route('challenges.index'), navigate: true);
    }

    public function render(ChallengePresetService $presets)
    {
        return view('livewire.challenges.form', [
            'habits' => auth()->user()->habits()->orderBy('title')->get(['id', 'title', 'color']),
            'typeLabels' => [
                'streak' => 'Серия дней подряд',
                'total_completions' => 'Всего отметок',
                'period_days' => 'Дней с отметками',
            ],
            'presets' => $presets->all(),
        ]);
    }
}
