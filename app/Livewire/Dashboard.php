<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\IrrigationMode;
use App\Models\ManualIrrigationSettings;
use App\Models\IrrigationTimerSetting;
use App\Models\Crop;
use App\Models\User;
use App\Models\ActivityLog;

class Dashboard extends Component
{
    public $mode = 'manual';

    public function mount()
    {
        $this->loadMode();
    }

    public function loadMode()
    {
        $mode = IrrigationMode::first();

        if ($mode) {
            $this->mode = $mode->mode;
        }
    }

    public function setMode($mode)
    {
        IrrigationMode::updateOrCreate(
            ['id' => 1],
            ['mode' => $mode]
        );

        $this->mode = $mode;
        $this->dispatch('modeChanged', mode: $mode);

        ManualIrrigationSettings::updateOrCreate(
            ['id' => 1],
            ['value' => 'OFF']
        );

        IrrigationTimerSetting::updateOrCreate(
            ['id' => 1],
            ['is_active' => 0]
        );

        Crop::query()->update(['state' => 0]);

        ActivityLog::create(['action' => 'Mode changed to ' . strtoupper($mode), 'type' => 'mode_change']);

        session()->flash('message', 'Mode set to ' . strtoupper($mode));
    }

    public function render()
    {
        $irrigationStatus = ManualIrrigationSettings::first()?->value ?? 'OFF';
        $activeCrop = Crop::where('state', 1)->first();
        $timerSetting = IrrigationTimerSetting::first();
        $totalCrops = Crop::count();
        $totalUsers = User::count();
        $activities = ActivityLog::latest()->take(10)->get();

        return view('livewire.dashboard', compact(
            'irrigationStatus', 'activeCrop', 'timerSetting',
            'totalCrops', 'totalUsers', 'activities'
        ));
    }
}