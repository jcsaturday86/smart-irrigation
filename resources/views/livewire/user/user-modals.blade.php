{{-- CREATE USER MODAL --}}
<flux:modal wire:model="showCreateModal" class="w-full max-w-md">
    <div class="space-y-6">
        <flux:heading size="lg">Create New User</flux:heading>

        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <flux:input wire:model.defer="name" label="Full Name" type="text" />
                @error('name') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <flux:input wire:model.defer="email" label="Email" type="email" />
                @error('email') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Role</label>
                <select wire:model.defer="role"
                        class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">Select Role</option>
                    <option value="User">User</option>
                    <option value="Admin">Administrator</option>
                </select>
                @error('role') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <flux:input wire:model.defer="password" label="Password" type="password" />
                @error('password') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <flux:input wire:model.defer="password_confirmation" label="Confirm Password" type="password" />
                @error('password_confirmation') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary"
                             x-data="{ clicked: false }"
                             x-on:click="if (clicked) $event.preventDefault(); clicked = true;"
                             wire:loading.attr="disabled"
                             wire:target="submit">
                    <span wire:loading.remove wire:target="submit">Register</span>
                    <span wire:loading wire:target="submit">Registering...</span>
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>

{{-- EDIT USER MODAL --}}
<flux:modal wire:model="showEditModal" class="w-full max-w-md">
    <div class="space-y-6">
        <flux:heading size="lg">Edit User</flux:heading>

        <div class="space-y-4">
            <div>
                <flux:input wire:model.defer="nameEdit" label="Name" type="text" />
                @error('nameEdit') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <flux:input wire:model.defer="emailEdit" label="Email" type="email" />
                @error('emailEdit') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Role</label>
                <select wire:model.defer="roleEdit"
                        class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    <option value="">Select Role</option>
                    <option value="User">User</option>
                    <option value="Admin">Administrator</option>
                </select>
                @error('roleEdit') <flux:text class="mt-1 text-sm !text-red-500">{{ $message }}</flux:text> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button wire:click="editSubmit" variant="primary">Update</flux:button>
        </div>
    </div>
</flux:modal>
