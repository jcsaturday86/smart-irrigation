<div class="space-y-6">
    <x-page-header
        icon="sun"
        title="Automatic Irrigation"
        subtitle="Manage crop moisture profiles for automatic watering" />

    <x-flash-message />

    {{-- Status Hero --}}
    <div class="ui-card">
        <div class="px-6 py-10 text-center {{ $activeCrop ? 'bg-emerald-50 dark:bg-emerald-950/30' : 'bg-zinc-50 dark:bg-zinc-800/40' }} transition-colors duration-500">
            <div class="relative mx-auto mb-5 flex h-24 w-24 items-center justify-center rounded-full {{ $activeCrop ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-colors duration-500">
                @if($activeCrop)
                    <span class="absolute inset-0 animate-ping rounded-full bg-emerald-400/40"></span>
                @endif
                <div class="relative flex h-16 w-16 items-center justify-center rounded-full shadow-lg {{ $activeCrop ? 'bg-emerald-500 shadow-emerald-500/40' : 'bg-zinc-400 shadow-zinc-400/20 dark:bg-zinc-500' }} transition-colors duration-500">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </div>
            </div>

            @if($activeCrop)
                <p class="text-2xl font-bold tracking-tight text-emerald-700 dark:text-emerald-400">{{ $activeCrop->crop_name }}</p>
                <flux:text class="mt-1 text-sm">Active crop — moisture range {{ $activeCrop->moisture_min }} – {{ $activeCrop->moisture_max }}</flux:text>
            @else
                <p class="text-2xl font-bold tracking-tight text-zinc-500 dark:text-zinc-400">No Active Crop</p>
                <flux:text class="mt-1 text-sm">Select a crop below to enable automatic irrigation</flux:text>
            @endif
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Mode</flux:text>
            <div class="mt-2">
                @if($mode == 'automatic')
                    <flux:badge color="lime" size="lg">Automatic</flux:badge>
                @else
                    <flux:badge color="zinc" size="lg">{{ ucfirst($mode) }}</flux:badge>
                @endif
            </div>
        </div>
        <div class="ui-tile">
            <flux:text class="ui-tile-label">Active Crop</flux:text>
            <div class="mt-2">
                @if($activeCrop)
                    <flux:badge color="lime" size="lg">{{ $activeCrop->crop_name }}</flux:badge>
                @else
                    <flux:text class="text-sm font-medium text-zinc-400">None</flux:text>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Edit Crop Form --}}
    <div class="ui-card">
        <div class="ui-card-header">
            <flux:heading size="lg">{{ $crop_id ? 'Edit Crop' : 'Add New Crop' }}</flux:heading>
            @if($crop_id)
                <flux:badge color="amber" size="sm">Editing</flux:badge>
            @endif
        </div>
        <div class="ui-card-body">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <flux:input wire:model.live="crop_name" label="Crop Name" placeholder="e.g. Tomato" />
                <flux:input wire:model.live="moisture_min" label="Min Moisture (Dry)" type="number" step="0.01" placeholder="0.00" />
                <flux:input wire:model.live="moisture_max" label="Max Moisture (Wet)" type="number" step="0.01" placeholder="0.00" />
            </div>

            <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                <button wire:click="save" class="btn-primary w-full sm:w-auto">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    {{ $crop_id ? 'Update' : 'Save' }}
                    <span wire:loading wire:target="save" class="btn-spinner bg-emerald-600">
                        <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                </button>
                <button wire:click="resetForm" class="btn-secondary w-full sm:w-auto">
                    Reset
                </button>
            </div>
        </div>
    </div>

    {{-- Crop List --}}
    <div class="ui-card">
        <div class="ui-card-header">
            <div>
                <flux:heading size="lg">Crops</flux:heading>
                <flux:text class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $crops->total() }} {{ Str::plural('profile', $crops->total()) }} saved</flux:text>
            </div>
            <div class="sm:max-w-xs sm:min-w-[220px]">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search crops..." icon="magnifying-glass" size="sm" />
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Name</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Min (Dry)</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Max (Wet)</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">State</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($crops as $crop)
                        <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="h-2 w-2 shrink-0 rounded-full {{ $crop->state ? 'bg-emerald-500' : 'bg-zinc-300 dark:bg-zinc-600' }}"></span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $crop->crop_name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center tabular-nums text-zinc-600 dark:text-zinc-400">{{ $crop->moisture_min }}</td>
                            <td class="px-5 py-3 text-center tabular-nums text-zinc-600 dark:text-zinc-400">{{ $crop->moisture_max }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($crop->state)
                                    <flux:badge color="lime" size="sm">Active</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Inactive</flux:badge>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center gap-1">
                                    @if($mode == "automatic")
                                        @if($crop->state == '1')
                                            <flux:button wire:click="setInactive({{ $crop->id }})"
                                                         onclick="confirm('Set this crop as inactive?') || event.stopImmediatePropagation()"
                                                         size="xs" variant="ghost">
                                                Set Inactive
                                            </flux:button>
                                        @else
                                            <flux:button wire:click="setActive({{ $crop->id }})"
                                                         onclick="confirm('Set this crop as active?') || event.stopImmediatePropagation()"
                                                         size="xs" variant="primary">
                                                Set Active
                                            </flux:button>
                                        @endif
                                    @else
                                        <flux:button disabled size="xs">Disabled</flux:button>
                                    @endif

                                    <flux:button wire:click="edit({{ $crop->id }})" size="xs" variant="ghost">
                                        Edit
                                    </flux:button>

                                    <flux:button wire:click="delete({{ $crop->id }})"
                                                 wire:confirm="Are you sure you want to delete this crop?"
                                                 size="xs" variant="danger">
                                        Delete
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                    </svg>
                                </div>
                                <flux:text class="text-zinc-500 dark:text-zinc-400">No crops found</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-zinc-200 sm:hidden dark:divide-zinc-800">
            @forelse($crops as $crop)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $crop->crop_name }}</flux:text>
                        @if($crop->state)
                            <flux:badge color="lime">Active</flux:badge>
                        @else
                            <flux:badge color="zinc">Inactive</flux:badge>
                        @endif
                    </div>
                    <div class="flex gap-4 text-sm">
                        <div>
                            <flux:text class="text-zinc-500">Min (Dry)</flux:text>
                            <flux:text class="font-medium">{{ $crop->moisture_min }}</flux:text>
                        </div>
                        <div>
                            <flux:text class="text-zinc-500">Max (Wet)</flux:text>
                            <flux:text class="font-medium">{{ $crop->moisture_max }}</flux:text>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        @if($mode == "automatic")
                            @if($crop->state == '1')
                                <flux:button wire:click="setInactive({{ $crop->id }})"
                                             onclick="confirm('Set this crop as inactive?') || event.stopImmediatePropagation()"
                                             size="xs" variant="ghost">Set Inactive</flux:button>
                            @else
                                <flux:button wire:click="setActive({{ $crop->id }})"
                                             onclick="confirm('Set this crop as active?') || event.stopImmediatePropagation()"
                                             size="xs" variant="primary">Set Active</flux:button>
                            @endif
                        @else
                            <flux:button disabled size="xs">Disabled</flux:button>
                        @endif
                        <flux:button wire:click="edit({{ $crop->id }})" size="xs" variant="ghost">Edit</flux:button>
                        <flux:button wire:click="delete({{ $crop->id }})"
                                     wire:confirm="Are you sure you want to delete this crop?"
                                     size="xs" variant="danger">Delete</flux:button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-zinc-500 dark:text-zinc-400">No crops found</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($crops->hasPages())
            <div class="border-t border-zinc-200 bg-zinc-50/60 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-800/30 sm:px-6">
                {{ $crops->links() }}
            </div>
        @endif
    </div>
</div>
