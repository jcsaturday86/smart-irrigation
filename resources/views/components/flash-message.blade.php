@if (session()->has('message'))
    <div x-data="{ show: true }"
         x-show="show"
         x-transition.opacity.duration.300ms
         x-init="setTimeout(() => show = false, 5000)"
         class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/40">
        <svg class="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>

        <span class="min-w-0 flex-1 text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('message') }}</span>

        <button type="button" @click="show = false"
                class="shrink-0 rounded-lg p-1 text-emerald-600 transition-colors hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40"
                aria-label="Dismiss">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif
