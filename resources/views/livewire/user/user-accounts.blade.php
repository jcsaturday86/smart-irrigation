<div class="space-y-6">
    <x-page-header
        icon="users"
        :title="__('User Accounts')"
        :subtitle="__('Manage who can access and operate the irrigation system')" />

    {{-- Flash Messages --}}
    @php
        $flashTypes = [
            'success' => ['border' => 'border-emerald-200 dark:border-emerald-900/60', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-800 dark:text-emerald-300', 'icon' => 'text-emerald-600 dark:text-emerald-400'],
            'warning' => ['border' => 'border-amber-200 dark:border-amber-900/60', 'bg' => 'bg-amber-50 dark:bg-amber-950/40', 'text' => 'text-amber-800 dark:text-amber-300', 'icon' => 'text-amber-600 dark:text-amber-400'],
            'error'   => ['border' => 'border-red-200 dark:border-red-900/60', 'bg' => 'bg-red-50 dark:bg-red-950/40', 'text' => 'text-red-800 dark:text-red-300', 'icon' => 'text-red-600 dark:text-red-400'],
            'message' => ['border' => 'border-emerald-200 dark:border-emerald-900/60', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-800 dark:text-emerald-300', 'icon' => 'text-emerald-600 dark:text-emerald-400'],
        ];
    @endphp

    @foreach ($flashTypes as $type => $styles)
        @if(session()->has($type))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.300ms
                 class="flex items-center gap-3 rounded-xl border {{ $styles['border'] }} {{ $styles['bg'] }} px-4 py-3 shadow-sm">
                <svg class="h-5 w-5 shrink-0 {{ $styles['icon'] }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    @if($type === 'error')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    @elseif($type === 'warning')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    @endif
                </svg>
                <span class="min-w-0 flex-1 text-sm font-medium {{ $styles['text'] }}">{{ session($type) }}</span>
                <button type="button" @click="show = false" class="shrink-0 rounded-lg p-1 opacity-60 transition-opacity hover:opacity-100 {{ $styles['icon'] }}" aria-label="Dismiss">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    @endforeach

    {{-- Modals --}}
    @include('livewire.user.user-modals')

    {{-- Main Content --}}
    <div class="ui-card">
        {{-- Header --}}
        <div class="ui-card-header">
            <div>
                <flux:heading size="lg">All Users</flux:heading>
                <flux:text class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $users->total() }} {{ Str::plural('account', $users->total()) }} registered</flux:text>
            </div>
            <button wire:click="$set('showCreateModal', true)" class="btn-primary w-full sm:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add User
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex flex-col gap-3 border-b border-zinc-200 bg-zinc-50/60 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-800/30 sm:flex-row sm:items-center sm:px-6">
            <div class="flex items-center gap-2">
                <flux:text class="text-sm whitespace-nowrap text-zinc-500 dark:text-zinc-400">Filter by</flux:text>
                <select wire:model="filterField"
                        class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-sm shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="role">Role</option>
                </select>
            </div>
            <div class="flex-grow sm:max-w-xs">
                <flux:input wire:model.live.debounce.50ms="search" placeholder="Search users..." icon="magnifying-glass" size="sm" />
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        @foreach(['name', 'email', 'role'] as $col)
                            <th wire:click="setSortBy('{{ $col }}')"
                                class="cursor-pointer select-none px-5 py-3 text-xs font-semibold uppercase tracking-wide text-zinc-500 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                <div class="flex items-center gap-1.5">
                                    {{ ucfirst($col) }}
                                    @if($sortBy === $col)
                                        <svg class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            @if($sortByDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            @endif
                                        </svg>
                                    @else
                                        <svg class="h-3.5 w-3.5 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($users as $user)
                        <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-600/10 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">
                                        {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                                    </span>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-zinc-600 dark:text-zinc-400">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                <flux:badge :color="$user->role === 'Admin' ? 'indigo' : 'zinc'" size="sm">{{ $user->role }}</flux:badge>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-1">
                                    <flux:button wire:click="editData({{ $user->id }})" size="xs" variant="ghost" icon="pencil" title="Edit User" />
                                    <flux:button wire:click="resetUserPassword({{ $user->id }})" size="xs" variant="ghost" icon="arrow-path" title="Reset Password" />
                                    @if($user->role !== 'Admin')
                                        @if($user->state == 1)
                                            <flux:button wire:click="disableUser({{ $user->id }})" size="xs" variant="danger" icon="x-mark" title="Disable User" />
                                        @else
                                            <flux:button wire:click="enableUser({{ $user->id }})" size="xs" variant="primary" icon="check" title="Enable User" />
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <svg class="h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </div>
                                <flux:text class="text-zinc-500 dark:text-zinc-400">No users found</flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-zinc-200 sm:hidden dark:divide-zinc-800">
            @forelse($users as $user)
                <div class="space-y-3 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-600/10 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">
                                {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                            </span>
                            <div class="min-w-0">
                                <flux:text class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</flux:text>
                                <flux:text class="truncate text-sm text-zinc-500">{{ $user->email }}</flux:text>
                            </div>
                        </div>
                        <flux:badge :color="$user->role === 'Admin' ? 'indigo' : 'zinc'" size="sm">{{ $user->role }}</flux:badge>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <flux:button wire:click="editData({{ $user->id }})" size="xs" variant="ghost" icon="pencil">Edit</flux:button>
                        <flux:button wire:click="resetUserPassword({{ $user->id }})" size="xs" variant="ghost" icon="arrow-path">Reset PW</flux:button>
                        @if($user->role !== 'Admin')
                            @if($user->state == 1)
                                <flux:button wire:click="disableUser({{ $user->id }})" size="xs" variant="danger" icon="x-mark">Disable</flux:button>
                            @else
                                <flux:button wire:click="enableUser({{ $user->id }})" size="xs" variant="primary" icon="check">Enable</flux:button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">No users found.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col gap-3 border-t border-zinc-200 bg-zinc-50/60 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-800/30 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex items-center gap-2">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Per page</flux:text>
                <select wire:change="changePerPage($event.target.value)"
                        class="rounded-lg border border-zinc-200 bg-white px-2 py-1 text-sm shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                    @foreach([5,10,20,50] as $num)
                        <option value="{{ $num }}">{{ $num }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
