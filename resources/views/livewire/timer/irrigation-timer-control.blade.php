<div class="p-4 sm:p-6">

    {{-- ALERT --}}
    @if(session()->has('message'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition
            class="flex items-center justify-between bg-green-100 text-green-700 p-3 mb-4 rounded"
        >
            <span>{{ session('message') }}</span>

            <button 
                @click="show = false"
                class="ml-4 text-green-700 hover:text-green-900 font-bold"
            >
                ✕
            </button>
        </div>
    @endif

    {{-- TITLE --}}
    <h2 class="text-xl sm:text-2xl font-bold mb-4">💧 Irrigation Timer Settings</h2>

    {{-- FORM --}}
    <div class="bg-white shadow rounded p-4 mb-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Duration --}}
            <input type="number" 
                wire:model.live="duration_minutes" 
                placeholder="Duration (minutes)" 
                class="border p-2 rounded w-full text-sm sm:text-base">

            {{-- Frequency --}}
            <input type="number" 
                wire:model.live="frequency_hours" 
                placeholder="Frequency (hours)" 
                class="border p-2 rounded w-full text-sm sm:text-base">

        </div>

        {{-- BUTTONS --}}
        <div class="mt-4 flex flex-col sm:flex-row gap-2">

            <button wire:click="save" 
                class="bg-green-600 text-white px-4 py-2 rounded w-full sm:w-auto">
                💾 Save Settings
            </button>

            <button wire:click="toggle" 
                class="px-4 py-2 rounded text-white w-full sm:w-auto
                {{ $is_active ? 'bg-red-500' : 'bg-blue-500' }}">
                
                {{ $is_active ? '⛔ Stop Irrigation' : '▶ Start Irrigation' }}
            </button>

        </div>
    </div>
    
    {{-- STATUS --}}
    <div class="mb-4">
        <div class="bg-gray-100 border border-gray-300 text-gray-600 p-3 rounded text-sm sm:text-base">
            💡 <strong>Status:</strong> 
            <span class="{{ $is_active ? 'text-green-600' : 'text-red-500' }}">
                {{ $is_active ? 'Running' : 'Stopped' }}
            </span>
        </div>
    </div>

    {{-- COUNTDOWN TIMER --}}
    <div 
        x-data="countdownTimer({{ $nextRunTimestamp ?? 0 }})"
        x-init="start()"
        class="mb-4"
    >

        <div class="bg-blue-100 border border-blue-300 text-blue-700 p-3 rounded text-sm sm:text-base">

            ⏱ <strong>Next Irrigation In:</strong>

            <span class="font-bold ml-2" x-text="formatted"></span>

        </div>

    </div>

    {{-- TABLE (History / Settings View) --}}
    <div class="overflow-x-auto">
        <table class="min-w-[600px] w-full border rounded text-sm sm:text-base">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-center">Duration</th>
                    <th class="p-2 text-center">Frequency</th>
                    <th class="p-2 text-center">Last Run</th>
                    <th class="p-2 text-center">State</th>
                </tr>
            </thead>

            <tbody>
                <tr class="border-t text-center">
                    <td class="p-2">
                        {{ $duration_minutes }} mins
                    </td>

                    <td class="p-2">
                        Every {{ $frequency_hours }} hrs
                    </td>

                    <td class="p-2">
                        {{ $setting->last_run_at ? $setting->last_run_at->format('M d, Y h:i A') : '—' }}
                    </td>

                    <td class="p-2">
                        <span class="{{ $is_active ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
function countdownTimer(nextRun) {
    return {
        nextRun: nextRun * 1000,
        formatted: 'Calculating...',
        interval: null,

        start() {
            this.update();
            this.interval = setInterval(() => {
                this.update();
            }, 1000);
        },

        update() {
            let now = new Date().getTime();
            let distance = this.nextRun - now;

            if (distance <= 0) {
                this.formatted = "Running soon...";
                return;
            }

            let hours = Math.floor(distance / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            this.formatted = `${hours}h ${minutes}m ${seconds}s`;
        }
    }
}
</script>