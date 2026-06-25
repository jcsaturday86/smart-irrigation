<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Create Account --}}
    @if(!$editing)
    <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Create an Account</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-4">Enter the details below to create an account</p>

        {{-- Session Messages --}}
        @foreach (['success' => 'green', 'warning' => 'yellow', 'error' => 'red'] as $type => $color)
            @if(session()->has($type))
                <div class="bg-{{ $color }}-100 dark:bg-{{ $color }}-200 border border-{{ $color }}-400 text-{{ $color }}-700 dark:text-{{ $color }}-900 px-4 py-3 rounded relative mb-4">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        {{-- Form --}}
        <form wire:submit="submit" class="space-y-4">
            @foreach ([['name', 'Full Name'], ['email', 'email@example.com']] as [$field, $placeholder])
            <div>
                <label class="block text-gray-700 dark:text-gray-300 capitalize">{{ $field }}</label>
                <input type="{{ $field == 'email' ? 'email' : 'text' }}"
                    wire:model="{{ $field }}"
                    class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300"
                    placeholder="{{ $placeholder }}" required autocomplete="{{ $field }}">
                @error($field) <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            @endforeach
            {{-- Role Dropdown --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Role</label>
                <select wire:model="role"
                    class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="">Select Role</option>
                    <option value="User">User</option>
                    <option value="Admin">Administrator</option>
                </select>
                @error('role') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            {{-- Passwords --}}
            @foreach ([['password', 'Password'], ['password_confirmation', 'Confirm Password']] as [$field, $placeholder])
            <div>
                <label class="block text-gray-700 dark:text-gray-300">{{ $placeholder }}</label>
                <input type="password" wire:model="{{ $field }}"
                    class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300"
                    placeholder="{{ $placeholder }}" required autocomplete="new-password">
                @error($field) <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            @endforeach
            <button type="submit"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow transition">
                Register
            </button>
        </form>
    </div>
    @endif

    {{-- Edit User --}}
    @if($editing)
    <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Edit User Information</h2>
        </div>
        <p class="text-gray-600 dark:text-gray-300 mb-4">Edit the details below to update the user</p>

        @foreach (['warning' => 'yellow', 'error' => 'red'] as $type => $color)
            @if(session()->has($type))
                <div class="bg-{{ $color }}-100 dark:bg-{{ $color }}-200 border border-{{ $color }}-400 text-{{ $color }}-700 dark:text-{{ $color }}-900 px-4 py-3 rounded relative mb-4">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        <form wire:submit.prevent="editSubmit" class="space-y-4">
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" wire:model="nameEdit" class="w-full px-2 py-2 border dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" wire:model="emailEdit" class="w-full px-2 py-2 border dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300" required>
            </div>
            <!-- <div>
                <label class="block text-gray-700 dark:text-gray-300">Role Sample</label>
                <input type="text" wire:model="userRoleEdit" class="w-full px-2 py-2 border dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300" required>
            </div> -->
            <div>
                <label class="block text-gray-700 dark:text-gray-300">Change Role</label>
                <select wire:model="roleEdit" 
                    class="w-full px-2 py-2 border dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring focus:ring-blue-300"
                    required>
                    <option value="">Select Role</option>
                    <option value="User">User</option>
                    <option value="Admin">Administrator</option>
                </select>
                @error('roleEdit') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <!-- <div class="flex justify-end gap-4 pt-4 pb-0"> -->
                <button type="button"
                    wire:click="$set('editing', false)"
                    class="px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 rounded-lg shadow-sm transition">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow transition">
                    Update
                </button>
            <!-- </div> -->
        </form>
    </div>
    @endif

    {{-- User Table --}}
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md mb-6">
            <div class="flex flex-wrap items-center justify-start gap-4 mb-4">
                {{-- Filter Dropdown --}}
                <div class="flex items-center gap-2">
                    <label for="filterField" class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">Filter By:</label>
                    <select id="filterField" wire:model="filterField"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring focus:ring-blue-500">
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="role">Role</option>
                    </select>
                </div>

                {{-- Search Input --}}
                <div class="flex-grow md:max-w-xs">
                    <input wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search User"
                        class="w-full pl-3 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
       
            <div class="overflow-hidden border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <table class="min-w-full bg-white dark:bg-gray-800 text-center text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @foreach(['name', 'email', 'role'] as $col)
                            <th class="px-6 py-3 text-gray-600 dark:text-gray-300 font-semibold cursor-pointer" wire:click="setSortBy('{{ $col }}')">
                                <div class="flex items-center justify-center">
                                    {{ ucfirst($col) }}
                                    @if($sortBy === $col)
                                        <i class="fas {{ $sortByDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }} ml-1"></i>
                                    @else
                                        <i class="fas fa-sort ml-1 text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            @endforeach
                            <th class="px-6 py-3 text-gray-600 dark:text-gray-300 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-2">{{ $user->name }}</td>
                            <td class="px-6 py-2">{{ $user->email }}</td>
                            <td class="px-6 py-2">{{ $user->role }}</td>
                            <td class="px-6 py-2">
                                <div class="flex gap-2 justify-center">
                                    <button wire:click="editData({{ $user->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded shadow">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </button>
                                    <button wire:click="resetUserPassword({{ $user->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded shadow">
                                        <x-heroicon-o-arrow-path class="h-4 w-4" />
                                    </button>
                                    @if($user->role !== 'Admin')
                                        @if($user->state === 1)
                                        <button wire:click="disableUser({{ $user->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded shadow">
                                            <x-heroicon-o-x-mark class="h-4 w-4" />
                                        </button>
                                        @else
                                        <button wire:click="enableUser({{ $user->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded shadow">
                                            <x-heroicon-o-check class="h-4 w-4" />
                                        </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Per Page:</label>
                        <select wire:change="changePerPage($event.target.value)" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach([5,10,20,50,100] as $num)
                                <option value="{{ $num }}">{{ $num }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>


