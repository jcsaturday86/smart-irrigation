<!-- resources/views/livewire/manual/manual-irrigation-control.blade.php -->

<div class="p-4 sm:p-6" wire:poll.10s="checkDeviceStatus">

    {{-- ALERT --}}
    @if(session()->has('message'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition
            class="flex items-center justify-between bg-green-100 text-green-700 p-3 mb-4 rounded"
        >
            <span>{{ session('message') }}</span>

            <button @click="show = false" class="font-bold">✕</button>
        </div>
    @endif

    {{-- TITLE --}}
    <h2 class="text-xl sm:text-2xl font-bold mb-4">💧 Manual Irrigation</h2>

    {{-- DEVICE STATUS --}}
    <div class="mb-4">
        <div class="bg-gray-100 border p-3 rounded text-sm sm:text-base">
            📡 <strong>Device Status:</strong>
            <span class="{{ $deviceStatus === 'ONLINE' ? 'text-green-600' : 'text-red-500' }}">
                {{ $deviceStatus }}
            </span>
        </div>
    </div>

    {{-- IRRIGATION STATUS --}}
    <div class="mb-4">
        <div class="bg-gray-100 border p-3 rounded text-sm sm:text-base">
            💡 <strong>Irrigation:</strong>
            <span class="{{ $is_running ? 'text-green-600' : 'text-red-500' }}">
                {{ $statusMessage }}
            </span>
        </div>
    </div>

    {{-- BUTTONS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <button wire:click="start"
            class="bg-green-600 text-white py-4 rounded text-lg">
            ▶ START
        </button>

        <button wire:click="stop"
            class="bg-red-600 text-white py-4 rounded text-lg">
            ⛔ STOP
        </button>

    </div>

</div>