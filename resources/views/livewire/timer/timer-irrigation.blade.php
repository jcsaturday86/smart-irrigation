<div wire:poll.5s class="space-y-6">
    <x-page-header
        icon="clock"
        title="Timer Irrigation"
        subtitle="Configure duration and frequency for automated watering" />

    <x-flash-message />

    {{-- Status Hero --}}
    <div class="ui-card">
        <div class="px-6 py-10 text-center {{ $status === 'Running' ? 'bg-emerald-50 dark:bg-emerald-950/30' : 'bg-zinc-50 dark:bg-zinc-800/40' }} transition-colors duration-500">
            <div class="relative mx-auto mb-5 flex h-24 w-24 items-center justify-center rounded-full {{ $status === 'Running' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-colors duration-500">
                @if($status === 'Running')
                    <span class="absolute inset-0 animate-ping rounded-full bg-emerald-400/40"></span>
                @endif
                <div class="relative flex h-16 w-16 items-center justify-center rounded-full shadow-lg {{ $status === 'Running' ? 'bg-emerald-500 shadow-emerald-500/40' : 'bg-zinc-400 shadow-zinc-400/20 dark:bg-zinc-500' }} transition-colors duration-500">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>

            <p class="text-2xl font-bold tracking-tight {{ $status === 'Running' ? 'text-emerald-700 dark:text-emerald-400' : 'text-zinc-500 dark:text-zinc-400' }} transition-colors duration-500">
                {{ $status === 'Running' ? 'Timer Active' : 'Timer Stopped' }}
            </p>
            <flux:text class="mt-1 text-sm">
                {{ $status === 'Running' ? 'Next: ' . $nextIrrigationIn : 'Configure and start below' }}
            </flux:text>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Status</flux:text>
            <div class="mt-2">
                @if($status === 'Running')
                    <flux:badge color="lime" size="lg">Running</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">Stopped</flux:badge>
                @endif
            </div>
        </div>
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Mode</flux:text>
            <div class="mt-2">
                @if($mode == 'timer')
                    <flux:badge color="lime" size="lg">Timer</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">{{ ucfirst($mode) }}</flux:badge>
                @endif
            </div>
        </div>
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Next Run</flux:text>
            <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $nextIrrigationIn }}</p>
        </div>
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Last Run</flux:text>
            <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $last_run_at }}</p>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="ui-card">
        <div class="ui-card-header">
            <flux:heading size="lg">Timer Settings</flux:heading>
        </div>
        <div class="ui-card-body">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <flux:input wire:model="duration_minutes" label="Duration (minutes)" type="number" min="1" />
                    @error('duration_minutes')
                        <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text>
                    @enderror
                </div>
                <div>
                    <flux:input wire:model="frequency_hours" label="Frequency (hours)" type="number" min="1" />
                    @error('frequency_hours')
                        <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                @if($mode == "timer")
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button wire:click="saveSettings" class="btn-secondary w-full sm:w-auto">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Save Settings
                            <span wire:loading wire:target="saveSettings" class="btn-spinner bg-white dark:bg-zinc-800">
                                <svg class="h-5 w-5 animate-spin text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                        </button>

                        @if($status !== 'Running')
                            <button wire:click="startIrrigationNow" class="btn-primary w-full sm:w-auto">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" /></svg>
                                Start Now
                                <span wire:loading wire:target="startIrrigationNow" class="btn-spinner bg-emerald-600">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </span>
                            </button>
                        @else
                            <button wire:click="stopIrrigation" class="btn-danger w-full sm:w-auto">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" /></svg>
                                Stop
                                <span wire:loading wire:target="stopIrrigation" class="btn-spinner bg-red-600">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="ui-notice">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <flux:text class="font-medium text-amber-800 dark:text-amber-300">Timer mode is not active</flux:text>
                        </div>
                        <flux:text class="mt-1 text-sm text-amber-700/80 dark:text-amber-400/70">Switch to Timer mode from the Dashboard to enable controls.</flux:text>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Timer Records --}}
    <div class="ui-card">
        <div class="ui-card-header">
            <flux:heading size="lg">Timer History</flux:heading>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Duration</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Frequency</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Last Run</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">State</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($timers as $timer)
                        <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $timer->duration_minutes }} mins</td>
                            <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">Every {{ $timer->frequency_hours }} hrs</td>
                            <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">
                                {{ $timer->last_run_at ? \Carbon\Carbon::parse($timer->last_run_at)->format('M d, Y h:i A') : '—' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($timer->state === 'Active')
                                    <flux:badge color="lime" size="sm">Active</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Inactive</flux:badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <flux:text class="text-zinc-500 dark:text-zinc-400">No timer data found</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-zinc-200 sm:hidden dark:divide-zinc-800">
            @forelse($timers as $timer)
                <div class="p-4 space-y-2">
                    <div class="flex justify-between">
                        <flux:text class="text-sm text-zinc-500">Duration</flux:text>
                        <flux:text class="font-medium">{{ $timer->duration_minutes }} mins</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="text-sm text-zinc-500">Frequency</flux:text>
                        <flux:text class="font-medium">Every {{ $timer->frequency_hours }} hrs</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="text-sm text-zinc-500">Last Run</flux:text>
                        <flux:text class="font-medium">{{ $timer->last_run_at ? \Carbon\Carbon::parse($timer->last_run_at)->format('M d, Y h:i A') : '—' }}</flux:text>
                    </div>
                    <div class="flex justify-between items-center">
                        <flux:text class="text-sm text-zinc-500">State</flux:text>
                        @if($timer->state === 'Active')
                            <flux:badge color="lime">Active</flux:badge>
                        @else
                            <flux:badge color="zinc">Inactive</flux:badge>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-zinc-500 dark:text-zinc-400">No timer data found.</div>
            @endforelse
        </div>
    </div>
</div>
