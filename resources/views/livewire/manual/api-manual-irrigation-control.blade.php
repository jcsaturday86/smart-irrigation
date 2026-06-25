<div class="space-y-6">
    <div>
        <flux:heading size="xl">Manual Irrigation</flux:heading>
        <flux:subheading>Toggle irrigation on or off manually</flux:subheading>
    </div>

    {{-- Main Control Card --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        {{-- Status Header --}}
        <div class="px-6 py-8 text-center {{ $status == 'ON' ? 'bg-emerald-50 dark:bg-emerald-950/30' : 'bg-zinc-50 dark:bg-zinc-800/50' }} transition-colors duration-500">
            {{-- Animated Status Indicator --}}
            <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full {{ $status == 'ON' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-colors duration-500">
                <div class="flex h-16 w-16 items-center justify-center rounded-full {{ $status == 'ON' ? 'bg-emerald-500' : 'bg-zinc-400 dark:bg-zinc-500' }} transition-colors duration-500">
                    @if($status == 'ON')
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    @else
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                        </svg>
                    @endif
                </div>
            </div>

            <p class="text-2xl font-bold {{ $status == 'ON' ? 'text-emerald-700 dark:text-emerald-400' : 'text-zinc-500 dark:text-zinc-400' }} transition-colors duration-500">
                {{ $status == 'ON' ? 'Irrigation Active' : 'Irrigation Off' }}
            </p>
            <flux:text class="mt-1 text-sm">
                {{ $status == 'ON' ? 'Water is currently flowing' : 'System is idle' }}
            </flux:text>
        </div>

        {{-- Toggle Action --}}
        <div class="px-6 py-6 text-center">
            @if($mode == "manual")
                @if($status == "OFF")
                    <button wire:click="toggle"
                            class="group relative inline-flex h-14 w-full max-w-xs items-center justify-center gap-2 rounded-xl bg-emerald-600 px-8 font-semibold text-white shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:bg-emerald-700 hover:shadow-emerald-500/40 active:scale-[0.98]">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                        </svg>
                        Turn On
                        <span wire:loading wire:target="toggle" class="absolute inset-0 flex items-center justify-center rounded-xl bg-emerald-600">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                @else
                    <button wire:click="toggle"
                            class="group relative inline-flex h-14 w-full max-w-xs items-center justify-center gap-2 rounded-xl bg-red-600 px-8 font-semibold text-white shadow-lg shadow-red-500/25 transition-all duration-200 hover:bg-red-700 hover:shadow-red-500/40 active:scale-[0.98]">
                        <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                        </svg>
                        Turn Off
                        <span wire:loading wire:target="toggle" class="absolute inset-0 flex items-center justify-center rounded-xl bg-red-600">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </span>
                    </button>
                @endif
            @else
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-800 dark:bg-amber-900/20">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <flux:text class="font-medium text-amber-700 dark:text-amber-400">Manual mode is not active</flux:text>
                    </div>
                    <flux:text class="mt-1 text-sm text-amber-600/80 dark:text-amber-400/70">Switch to Manual mode from the Dashboard to enable controls.</flux:text>
                </div>
            @endif
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Status</flux:text>
            <div class="mt-2">
                @if($status == 'ON')
                    <flux:badge color="lime" size="lg">ON</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">OFF</flux:badge>
                @endif
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Mode</flux:text>
            <div class="mt-2">
                @if($mode == 'manual')
                    <flux:badge color="lime" size="lg">Manual</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">{{ ucfirst($mode) }}</flux:badge>
                @endif
            </div>
        </div>
    </div>
</div>
