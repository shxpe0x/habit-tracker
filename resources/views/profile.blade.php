<x-app-layout>
    <div class="mb-6">
        <flux:heading size="xl" level="1">Профиль</flux:heading>
        <flux:text class="mt-1">Управление аккаунтом и безопасностью</flux:text>
    </div>

    <div class="max-w-2xl space-y-4 sm:space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
            <livewire:profile.update-profile-information-form />
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
            <livewire:profile.update-password-form />
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 sm:p-6">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
