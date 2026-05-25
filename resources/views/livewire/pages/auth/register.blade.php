<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">Регистрация</flux:heading>
        <flux:text class="mt-1">Создайте новый аккаунт</flux:text>
    </div>

    <form wire:submit="register" class="space-y-5">
        <flux:input
            wire:model="name"
            label="Имя"
            required
            autofocus
            autocomplete="name"
        />

        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autocomplete="username"
        />

        <flux:input
            wire:model="password"
            label="Пароль"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model="password_confirmation"
            label="Подтверждение пароля"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Зарегистрироваться
        </flux:button>
    </form>

    <flux:separator variant="subtle" />

    <div class="text-center">
        <flux:text size="sm">
            Уже есть аккаунт?
            <flux:link :href="route('login')" wire:navigate>Войти</flux:link>
        </flux:text>
    </div>
</div>
