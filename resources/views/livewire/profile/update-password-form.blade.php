<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <flux:heading size="lg">Смена пароля</flux:heading>
        <flux:text class="mt-1">Используйте надёжный пароль для безопасности аккаунта.</flux:text>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-5">
        <flux:input
            wire:model="current_password"
            label="Текущий пароль"
            type="password"
            autocomplete="current-password"
            viewable
        />

        <flux:input
            wire:model="password"
            label="Новый пароль"
            type="password"
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model="password_confirmation"
            label="Подтверждение пароля"
            type="password"
            autocomplete="new-password"
            viewable
        />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Сохранить</flux:button>

            <span x-data="{ shown: false }"
                  x-on:password-updated.window="shown = true; setTimeout(() => shown = false, 2000)"
                  x-show="shown"
                  x-transition.opacity
                  class="text-sm text-zinc-500">
                Сохранено
            </span>
        </div>
    </form>
</section>
