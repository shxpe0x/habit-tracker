<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';
    public bool $confirmingUserDeletion = false;

    public function confirmUserDeletion(): void
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <flux:heading size="lg" class="text-red-600 dark:text-red-400">Удаление аккаунта</flux:heading>
        <flux:text class="mt-1">
            После удаления все данные будут безвозвратно стёрты. Скачайте всё, что хотите сохранить.
        </flux:text>
    </header>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger">Удалить аккаунт</flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">Точно удалить аккаунт?</flux:heading>
                <flux:text class="mt-2">
                    После удаления все ресурсы и данные будут стёрты. Введите пароль для подтверждения.
                </flux:text>
            </div>

            <flux:input
                wire:model="password"
                label="Пароль"
                type="password"
                viewable
            />

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Отмена</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger">Удалить аккаунт</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
