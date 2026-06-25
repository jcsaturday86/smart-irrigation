<div class="space-y-6">
    {{-- Flash Messages --}}
    @php
        $flashTypes = [
            'success' => ['border' => 'border-green-200 dark:border-green-700', 'bg' => 'bg-green-50 dark:bg-green-900/20', 'text' => 'text-green-700 dark:text-green-400'],
            'warning' => ['border' => 'border-yellow-200 dark:border-yellow-700', 'bg' => 'bg-yellow-50 dark:bg-yellow-900/20', 'text' => 'text-yellow-700 dark:text-yellow-400'],
            'error'   => ['border' => 'border-red-200 dark:border-red-700', 'bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-700 dark:text-red-400'],
            'message' => ['border' => 'border-green-200 dark:border-green-700', 'bg' => 'bg-green-50 dark:bg-green-900/20', 'text' => 'text-green-700 dark:text-green-400'],
        ];
    @endphp

    @foreach ($flashTypes as $type => $styles)
        @if(session()->has($type))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="flex items-center justify-between rounded-lg border {{ $styles['border'] }} {{ $styles['bg'] }} px-4 py-3 text-sm {{ $styles['text'] }}">
                <span>{{ session($type) }}</span>
                <button @click="show = false" class="ml-4 opacity-60 hover:opacity-100">&times;</button>
            </div>
        @endif
    @endforeach

    {{-- Modals --}}
    @include('livewire.user.user-modals')

    {{-- Main Content --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        {{-- Header --}}
        <div class="flex flex-col gap-4 border-b border-zinc-200 p-6 dark:border-zinc-700 sm:flex-row sm:items-center sm:justify-between">
            <flux:heading size="lg">User Accounts</flux:heading>
            <flux:button wire:click="$set('showCreateModal', true)" variant="primary" icon="plus">
                Add User
            </flux:button>
        </div>

        {{-- Filters --}}
        <div class="flex flex-col gap-3 border-b border-zinc-200 px-6 py-4 dark:border-zinc-700 sm:flex-row sm:items-center">
            <div class="flex items-center gap-2">
                <flux:text class="text-sm whitespace-nowrap">Filter By:</flux:text>
                <select wire:model="filterField"
                        class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-sm dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="role">Role</option>
                </select>
            </div>
            <div class="flex-grow sm:max-w-xs">
                <flux:input wire:model.live.debounce.50ms="search" placeholder="Search User" icon="magnifying-glass" size="sm" />
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden sm:block">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        @foreach(['name', 'email', 'role'] as $col)
                            <th wire:click="setSortBy('{{ $col }}')" class="cursor-pointer px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">
                                <div class="flex items-center gap-1">
                                    {{ ucfirst($col) }}
                                    @if($sortBy === $col)
                                        <i class="fas {{ $sortByDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} text-xs"></i>
                                    @else
                                        <i class="fas fa-sort text-xs text-zinc-400"></i>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                        <th class="px-4 py-3 font-medium text-zinc-600 dark:text-zinc-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1">
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
                            <td colspan="4" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse($users as $user)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:text class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</flux:text>
                            <flux:text class="text-sm text-zinc-500">{{ $user->email }}</flux:text>
                        </div>
                        <flux:badge color="{{ $user->role === 'Admin' ? 'indigo' : 'zinc' }}">{{ $user->role }}</flux:badge>
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
                <div class="p-4 text-center text-zinc-500 dark:text-zinc-400">No users found.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col gap-3 border-t border-zinc-200 px-6 py-4 dark:border-zinc-700 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <flux:text class="text-sm">Per Page:</flux:text>
                <select wire:change="changePerPage($event.target.value)"
                        class="rounded-lg border border-zinc-200 bg-white px-2 py-1 text-sm dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
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
