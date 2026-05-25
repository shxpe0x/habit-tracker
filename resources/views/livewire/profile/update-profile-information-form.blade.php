<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('profile-status', 'Сохранено');
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <flux:heading size="lg">Информация профиля</flux:heading>
        <flux:text class="mt-1">Обновите имя и email.</flux:text>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-5">
        <flux:input wire:model="name" label="Имя" required autocomplete="name" />

        <flux:input wire:model="email" label="Email" type="email" required autocomplete="username" />

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <flux:callout variant="warning" icon="exclamation-triangle">
                <flux:callout.text>
                    Email не подтверждён.
                    <button wire:click.prevent="sendVerification" class="underline ml-1">
                        Отправить ссылку повторно
                    </button>
                </flux:callout.text>
            </flux:callout>

            @if (session('status') === 'verification-link-sent')
                <flux:callout variant="success" icon="check-circle">
                    <flux:callout.text>Ссылка отправлена на ваш email.</flux:callout.text>
                </flux:callout>
            @endif
        @endif

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Сохранить</flux:button>

            <span x-data="{ shown: false }"
                  x-on:profile-updated.window="shown = true; setTimeout(() => shown = false, 2000)"
                  x-show="shown"
                  x-transition.opacity
                  class="text-sm text-zinc-500">
                Сохранено
            </span>
        </div>
    </form>
</section>
