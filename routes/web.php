<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Crops\CropManager;
use App\Livewire\Timer\TimerIrrigation;

// Redirect root to login if guest
Route::redirect('/', 'login')->middleware('guest')->name('home');

// Dashboard (authenticated and verified users)
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/generate-schedule/{id}', [SchedulingController::class, 'generate'])
    ->middleware(['auth', 'verified'])
    ->name('generate-schedule');
    

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/crops', CropManager::class)->name('crops');
    //Route::get('/irrigation_timer_settings', CropManager::class)->name('crops');
});

Route::get('/irrigation_timer_settings', function () {
    return view('irrigation-timer');
});

Route::get('/manual_irrigation', function () {
    return view('manual-irrigation');
});

Route::get('/manual_irrigation_toggle', function () {
    return view('manual-irrigation-toggle');
});

Route::get('/timer-irrigation', TimerIrrigation::class)->name('timer.irrigation');

// Group all settings under /settings with auth & verified middleware
Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    // Static blade views
    Route::view('/user-accounts', 'user-accounts')->name('user-accounts');
    
    // Livewire route

    // Profile settings
    Route::redirect('/', 'profile');
    Route::get('/profile', Profile::class)->name('settings.profile');
    Route::get('/password', Password::class)->name('settings.password');
    Route::get('/appearance', Appearance::class)->name('settings.appearance');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

// Include auth routes
require __DIR__.'/auth.php';
