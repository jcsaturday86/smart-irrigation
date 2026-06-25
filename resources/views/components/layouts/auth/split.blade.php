<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            {{-- Left panel — irrigation hero --}}
            <div class="relative hidden h-full flex-col overflow-hidden lg:flex">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-500"></div>
                <div class="absolute inset-0 opacity-10">
                    <svg class="h-full w-full" viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="400" cy="300" r="200" fill="none" stroke="white" stroke-width="1" opacity="0.3"/>
                        <circle cx="400" cy="300" r="300" fill="none" stroke="white" stroke-width="0.5" opacity="0.2"/>
                        <circle cx="400" cy="300" r="400" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/>
                    </svg>
                </div>

                <div class="relative z-20 p-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-medium text-white" wire:navigate>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                            <x-app-logo-icon class="h-6 w-6 fill-current text-white" />
                        </span>
                        {{ config('app.name', 'Smart Irrigation') }}
                    </a>
                </div>

                <div class="relative z-20 flex flex-1 flex-col items-center justify-center px-16">
                    <x-app-logo-icon class="mb-8 h-32 w-32 fill-current text-white/90" />
                    <h2 class="text-center text-3xl font-bold text-white">Smart Water Management</h2>
                    <p class="mt-3 max-w-sm text-center text-base text-emerald-100/80">Monitor and control your irrigation system with manual, timer, and automatic modes.</p>
                </div>

                <div class="relative z-20 p-10">
                    <div class="flex items-center gap-6 text-sm text-emerald-100/60">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-emerald-300"></div>
                            <span>Manual Control</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-teal-300"></div>
                            <span>Timer Mode</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-cyan-300"></div>
                            <span>Automatic</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right panel — form --}}
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-3 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-500">
                            <x-app-logo-icon class="size-7 fill-current text-white" />
                        </span>
                        <span class="text-lg font-semibold text-zinc-800 dark:text-white">{{ config('app.name', 'Smart Irrigation') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
