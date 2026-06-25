<div wire:poll.10s class="space-y-6">
    <div>
        <flux:heading size="xl">Dashboard</flux:heading>
        <flux:subheading>Monitor and control your irrigation system</flux:subheading>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-900/20 dark:text-green-400">
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="ml-4 text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    {{-- Mode Selector Hero --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="px-6 py-8 text-center bg-zinc-50 dark:bg-zinc-800/50">
            <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500">
                    @if($mode == 'manual')
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 0-.668-.668 1.667 1.667 0 0 0-1.667 1.667v-1.5" />
                        </svg>
                    @elseif($mode == 'timer')
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    @else
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    @endif
                </div>
            </div>
            <p class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">{{ ucfirst($mode) }} Mode</p>
            <flux:text class="mt-1 text-sm">
                @if($mode == 'manual') Direct on/off control
                @elseif($mode == 'timer') Scheduled automated watering
                @else Crop-based smart watering
                @endif
            </flux:text>
        </div>

        <div class="px-6 py-6">
            <flux:text class="mb-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Switch Mode</flux:text>
            <div class="grid grid-cols-3 gap-3">
                <button wire:click="setMode('manual')"
                        class="group relative flex flex-col items-center gap-2 rounded-xl px-4 py-4 font-medium transition-all duration-200 active:scale-[0.97]
                        {{ $mode == 'manual'
                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/25'
                            : 'border border-zinc-200 text-zinc-600 hover:border-emerald-300 hover:bg-emerald-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/20' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 0-.668-.668 1.667 1.667 0 0 0-1.667 1.667v-1.5" />
                    </svg>
                    <span class="text-sm">Manual</span>
                    <span wire:loading wire:target="setMode('manual')" class="absolute inset-0 flex items-center justify-center rounded-xl {{ $mode == 'manual' ? 'bg-emerald-600' : 'bg-white dark:bg-zinc-900' }}">
                        <svg class="h-5 w-5 animate-spin {{ $mode == 'manual' ? 'text-white' : 'text-emerald-600' }}" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>

                <button wire:click="setMode('timer')"
                        class="group relative flex flex-col items-center gap-2 rounded-xl px-4 py-4 font-medium transition-all duration-200 active:scale-[0.97]
                        {{ $mode == 'timer'
                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/25'
                            : 'border border-zinc-200 text-zinc-600 hover:border-emerald-300 hover:bg-emerald-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/20' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm">Timer</span>
                    <span wire:loading wire:target="setMode('timer')" class="absolute inset-0 flex items-center justify-center rounded-xl {{ $mode == 'timer' ? 'bg-emerald-600' : 'bg-white dark:bg-zinc-900' }}">
                        <svg class="h-5 w-5 animate-spin {{ $mode == 'timer' ? 'text-white' : 'text-emerald-600' }}" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>

                <button wire:click="setMode('automatic')"
                        class="group relative flex flex-col items-center gap-2 rounded-xl px-4 py-4 font-medium transition-all duration-200 active:scale-[0.97]
                        {{ $mode == 'automatic'
                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/25'
                            : 'border border-zinc-200 text-zinc-600 hover:border-emerald-300 hover:bg-emerald-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/20' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <span class="text-sm">Auto</span>
                    <span wire:loading wire:target="setMode('automatic')" class="absolute inset-0 flex items-center justify-center rounded-xl {{ $mode == 'automatic' ? 'bg-emerald-600' : 'bg-white dark:bg-zinc-900' }}">
                        <svg class="h-5 w-5 animate-spin {{ $mode == 'automatic' ? 'text-white' : 'text-emerald-600' }}" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- System Status Overview --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <flux:heading size="lg">System Status</flux:heading>
        </div>
        <div class="grid grid-cols-2 gap-px bg-zinc-200 dark:bg-zinc-700 lg:grid-cols-4">
            {{-- Irrigation --}}
            <div class="flex flex-col items-center gap-2 bg-white p-5 dark:bg-zinc-900">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $irrigationStatus === 'ON' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-100 dark:bg-zinc-800' }}">
                    <svg class="h-5 w-5 {{ $irrigationStatus === 'ON' ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </div>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Irrigation</flux:text>
                @if($irrigationStatus === 'ON')
                    <flux:badge color="lime">ON</flux:badge>
                @else
                    <flux:badge color="zinc">OFF</flux:badge>
                @endif
            </div>

            {{-- Active Mode --}}
            <div class="flex flex-col items-center gap-2 bg-white p-5 dark:bg-zinc-900">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/40">
                    <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.431.992a7.723 7.723 0 0 1 0 .255c-.007.378.138.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Active Mode</flux:text>
                <flux:badge color="indigo">{{ strtoupper($mode) }}</flux:badge>
            </div>

            {{-- Active Crop --}}
            <div class="flex flex-col items-center gap-2 bg-white p-5 dark:bg-zinc-900">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $activeCrop ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-100 dark:bg-zinc-800' }}">
                    <svg class="h-5 w-5 {{ $activeCrop ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </div>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Active Crop</flux:text>
                @if($activeCrop)
                    <flux:badge color="lime">{{ $activeCrop->crop_name }}</flux:badge>
                @else
                    <flux:text class="text-sm font-medium text-zinc-400">None</flux:text>
                @endif
            </div>

            {{-- Timer --}}
            <div class="flex flex-col items-center gap-2 bg-white p-5 dark:bg-zinc-900">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $timerSetting && $timerSetting->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-100 dark:bg-zinc-800' }}">
                    <svg class="h-5 w-5 {{ $timerSetting && $timerSetting->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Timer</flux:text>
                @if($timerSetting && $timerSetting->is_active)
                    <flux:badge color="lime">Running</flux:badge>
                @else
                    <flux:badge color="zinc">Stopped</flux:badge>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <flux:heading size="lg">Quick Stats</flux:heading>
        </div>
        <div class="grid grid-cols-3 gap-px bg-zinc-200 dark:bg-zinc-700">
            <div class="flex flex-col items-center gap-1 bg-white p-5 dark:bg-zinc-900">
                <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalCrops }}</p>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Crops</flux:text>
            </div>
            <div class="flex flex-col items-center gap-1 bg-white p-5 dark:bg-zinc-900">
                <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalUsers }}</p>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Users</flux:text>
            </div>
            <div class="flex flex-col items-center gap-1 bg-white px-4 py-5 dark:bg-zinc-900">
                <p class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $timerSetting && $timerSetting->last_run_at ? \Carbon\Carbon::parse($timerSetting->last_run_at)->shortRelativeDiffForHumans() : '—' }}
                </p>
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">Last Run</flux:text>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
            <flux:heading size="lg">Recent Activity</flux:heading>
        </div>
        @if($activities->count())
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @foreach($activities as $activity)
                    <div class="flex items-center justify-between gap-3 px-5 py-3.5">
                        <div class="flex items-center gap-3 min-w-0">
                            @php
                                $icons = [
                                    'mode_change' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.431.992a7.723 7.723 0 0 1 0 .255c-.007.378.138.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
                                    'irrigation' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />',
                                    'timer' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                                    'crop' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />',
                                ];
                                $bgColors = [
                                    'mode_change' => 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400',
                                    'irrigation' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
                                    'timer' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400',
                                    'crop' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400',
                                ];
                                $bg = $bgColors[$activity->type] ?? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400';
                                $icon = $icons[$activity->type] ?? $icons['irrigation'];
                            @endphp
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $bg }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">{!! $icon !!}</svg>
                            </div>
                            <div class="min-w-0">
                                <flux:text class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $activity->action }}</flux:text>
                                <flux:text class="text-xs text-zinc-400">{{ $activity->created_at->diffForHumans() }}</flux:text>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-10 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <flux:text class="text-zinc-400">No activity yet</flux:text>
                <flux:text class="mt-1 text-xs text-zinc-400">Mode changes, irrigation toggles, and crop updates will appear here.</flux:text>
            </div>
        @endif
    </div>
</div>
