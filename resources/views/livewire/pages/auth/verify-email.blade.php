<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div>
        <flux:heading size="lg">Подтверждение email</flux:heading>
        <flux:text class="mt-1">
            Спасибо за регистрацию! Перед началом работы подтвердите свой email — мы отправили ссылку на ваш адрес. Если письмо не пришло, отправим ещё раз.
        </flux:text>
    </div>

    @if (session('status') == 'verification-link-sent')
        <flux:callout variant="success" icon="check-circle">
            <flux:callout.text>
                Новая ссылка для подтверждения отправлена на ваш email.
            </flux:callout.text>
        </flux:callout>
    @endif

    <div class="flex items-center justify-between">
        <flux:button wire:click="sendVerification" variant="primary">
            Отправить повторно
        </flux:button>

        <flux:button wire:click="logout" variant="ghost" size="sm">
            Выйти
        </flux:button>
    </div>
</div>
