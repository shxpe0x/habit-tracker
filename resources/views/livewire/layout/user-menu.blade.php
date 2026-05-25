<div>
    <flux:dropdown position="bottom" align="end">
        <flux:profile :name="auth()->user()->name" :initials="\Illuminate\Support\Str::of(auth()->user()->name)->substr(0, 1)->upper()" />

        <flux:menu>
            <flux:menu.radio.group variant="standalone">
                <flux:menu.radio :checked="auth()->user()->name" disabled>
                    {{ auth()->user()->name }}
                </flux:menu.radio>
            </flux:menu.radio.group>
            <flux:menu.separator />

            <flux:menu.item icon="user-circle" :href="route('profile')" wire:navigate>
                Профиль
            </flux:menu.item>

            <flux:menu.submenu heading="Тема" icon="sun">
                <flux:menu.radio.group x-data="{ value: $flux.appearance }" x-model="value" @change="$flux.appearance = value">
                    <flux:menu.radio value="light">Светлая</flux:menu.radio>
                    <flux:menu.radio value="dark">Тёмная</flux:menu.radio>
                    <flux:menu.radio value="system">Системная</flux:menu.radio>
                </flux:menu.radio.group>
            </flux:menu.submenu>

            <flux:menu.separator />

            <flux:menu.item icon="arrow-right-start-on-rectangle" wire:click="logout" variant="danger">
                Выйти
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</div>
