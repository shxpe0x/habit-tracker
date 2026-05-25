<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">Вход</flux:heading>
        <flux:text class="mt-1">Войдите в свой аккаунт</flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            <flux:callout.text>{{ session('status') }}</flux:callout.text>
        </flux:callout>
    @endif

    <form wire:submit="login" class="space-y-5">
        <flux:input
            wire:model="form.email"
            label="Email"
            type="email"
            required
            autofocus
            autocomplete="username"
        />

        <flux:input
            wire:model="form.password"
            label="Пароль"
            type="password"
            required
            autocomplete="current-password"
            viewable
        />

        <div class="flex items-center justify-between">
            <flux:checkbox wire:model="form.remember" label="Запомнить меня" />

            @if (Route::has('password.request'))
                <flux:link :href="route('password.request')" wire:navigate variant="subtle" class="text-sm">
                    Забыли пароль?
                </flux:link>
            @endif
        </div>

        <flux:button type="submit" variant="primary" class="w-full">
            Войти
        </flux:button>
    </form>

    <flux:separator variant="subtle" />

    <div class="text-center">
        <flux:text size="sm">
            Нет аккаунта?
            <flux:link :href="route('register')" wire:navigate>Зарегистрироваться</flux:link>
        </flux:text>
    </div>
</div>
