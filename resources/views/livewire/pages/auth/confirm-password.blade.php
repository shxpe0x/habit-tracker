<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">Подтверждение пароля</flux:heading>
        <flux:text class="mt-1">
            Это защищённая область. Подтвердите пароль, чтобы продолжить.
        </flux:text>
    </div>

    <form wire:submit="confirmPassword" class="space-y-5">
        <flux:input
            wire:model="password"
            label="Пароль"
            type="password"
            required
            autocomplete="current-password"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Подтвердить
        </flux:button>
    </form>
</div>
