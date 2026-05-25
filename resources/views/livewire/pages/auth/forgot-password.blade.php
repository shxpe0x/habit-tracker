<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">Восстановление пароля</flux:heading>
        <flux:text class="mt-1">
            Введите email, и мы отправим ссылку для сброса пароля.
        </flux:text>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">
            <flux:callout.text>{{ session('status') }}</flux:callout.text>
        </flux:callout>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autofocus
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Отправить ссылку
        </flux:button>
    </form>

    <div class="text-center">
        <flux:link :href="route('login')" wire:navigate variant="subtle" class="text-sm">
            ← Вернуться ко входу
        </flux:link>
    </div>
</div>
