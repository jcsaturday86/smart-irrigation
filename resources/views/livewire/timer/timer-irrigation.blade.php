<div wire:poll.5s class="space-y-6">
    <div>
        <flux:heading size="xl">Timer Irrigation</flux:heading>
        <flux:subheading>Configure duration and frequency for automated watering</flux:subheading>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-900/20 dark:text-green-400">
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="ml-4 text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    {{-- Status Hero --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="px-6 py-8 text-center {{ $status === 'Running' ? 'bg-emerald-50 dark:bg-emerald-950/30' : 'bg-zinc-50 dark:bg-zinc-800/50' }} transition-colors duration-500">
            <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full {{ $status === 'Running' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-colors duration-500">
                <div class="flex h-16 w-16 items-center justify-center rounded-full {{ $status === 'Running' ? 'bg-emerald-500' : 'bg-zinc-400 dark:bg-zinc-500' }} transition-colors duration-500">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>

            <p class="text-2xl font-bold {{ $status === 'Running' ? 'text-emerald-700 dark:text-emerald-400' : 'text-zinc-500 dark:text-zinc-400' }} transition-colors duration-500">
                {{ $status === 'Running' ? 'Timer Active' : 'Timer Stopped' }}
            </p>
            <flux:text class="mt-1 text-sm">
                {{ $status === 'Running' ? 'Next: ' . $nextIrrigationIn : 'Configure and start below' }}
            </flux:text>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Status</flux:text>
            <div class="mt-2">
                @if($status === 'Running')
                    <flux:badge color="lime" size="lg">Running</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">Stopped</flux:badge>
                @endif
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Mode</flux:text>
            <div class="mt-2">
                @if($mode == 'timer')
                    <flux:badge color="lime" size="lg">Timer</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">{{ ucfirst($mode) }}</flux:badge>
                @endif
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Next Run</flux:text>
            <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $nextIrrigationIn }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Last Run</flux:text>
            <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $last_run_at }}</p>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <flux:heading size="lg">Timer Settings</flux:heading>
        </div>
        <div class="p-6">
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
                        <flux:button wire:click="saveSettings" variant="primary" icon="check" class="w-full sm:w-auto">
                            Save Settings
                        </flux:button>

                        @if($status !== 'Running')
                            <button wire:click="startIrrigationNow"
                                    class="group relative inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 font-semibold text-white shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:bg-emerald-700 hover:shadow-emerald-500/40 active:scale-[0.98] sm:w-auto">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" /></svg>
                                Start Now
                                <span wire:loading wire:target="startIrrigationNow" class="absolute inset-0 flex items-center justify-center rounded-xl bg-emerald-600">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </span>
                            </button>
                        @else
                            <button wire:click="stopIrrigation"
                                    class="group relative inline-flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-6 font-semibold text-white shadow-lg shadow-red-500/25 transition-all duration-200 hover:bg-red-700 hover:shadow-red-500/40 active:scale-[0.98] sm:w-auto">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" /></svg>
                                Stop
                                <span wire:loading wire:target="stopIrrigation" class="absolute inset-0 flex items-center justify-center rounded-xl bg-red-600">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-800 dark:bg-amber-900/20">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <flux:text class="font-medium text-amber-700 dark:text-amber-400">Timer mode is not active</flux:text>
                        </div>
                        <flux:text class="mt-1 text-sm text-amber-600/80 dark:text-amber-400/70 text-center">Switch to Timer mode from the Dashboard to enable controls.</flux:text>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Timer Records --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <flux:heading size="lg">Timer History</flux:heading>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">Duration</th>
                        <th class="px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">Frequency</th>
                        <th class="px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">Last Run</th>
                        <th class="px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">State</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($timers as $timer)
                        <tr>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">{{ $timer->duration_minutes }} mins</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">Every {{ $timer->frequency_hours }} hrs</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">
                                {{ $timer->last_run_at ? \Carbon\Carbon::parse($timer->last_run_at)->format('M d, Y h:i A') : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($timer->state === 'Active')
                                    <flux:badge color="lime">Active</flux:badge>
                                @else
                                    <flux:badge color="zinc">Inactive</flux:badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No timer data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
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
