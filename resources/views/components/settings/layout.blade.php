<div>
    <div class="flex items-start gap-8 max-md:flex-col max-md:gap-4">
        <div class="w-full md:w-[220px] md:shrink-0">
            <div class="ui-card p-2">
                <flux:navlist>
                    <flux:navlist.item :href="route('settings.profile')" icon="user" :current="request()->routeIs('settings.profile')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
                    <flux:navlist.item :href="route('settings.password')" icon="key" :current="request()->routeIs('settings.password')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
                    <flux:navlist.item :href="route('settings.appearance')" icon="swatch" :current="request()->routeIs('settings.appearance')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
                </flux:navlist>
            </div>
        </div>

        <div class="w-full min-w-0 flex-1">
            <div class="ui-card">
                <div class="ui-card-header">
                    <div>
                        <flux:heading size="lg">{{ $heading ?? '' }}</flux:heading>
                        @if (!empty($subheading))
                            <flux:text class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $subheading }}</flux:text>
                        @endif
                    </div>
                </div>

                <div class="ui-card-body">
                    <div class="w-full max-w-lg">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
