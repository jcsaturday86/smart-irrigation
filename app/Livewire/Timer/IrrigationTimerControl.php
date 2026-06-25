<?php

namespace App\Livewire\Timer;

use Livewire\Component;
use App\Models\IrrigationTimerSetting;
use Illuminate\Support\Facades\Http;

class IrrigationTimerControl extends Component
{
    public $duration_minutes;
    public $frequency_hours;
    public $is_active;

    public $setting;
    public $nextRunTimestamp;

    public function mount()
    {
        $this->setting = IrrigationTimerSetting::first();

        if (!$this->setting) {
            $this->setting = IrrigationTimerSetting::create([
                'duration_minutes' => 10,
                'frequency_hours' => 6,
                'is_active' => false,
            ]);
        }

        $this->duration_minutes = $this->setting->duration_minutes;
        $this->frequency_hours = $this->setting->frequency_hours;
        $this->is_active = $this->setting->is_active;

        $this->computeNextRun();
    }

    public function save()
    {
        $this->setting->update([
            'duration_minutes' => $this->duration_minutes,
            'frequency_hours' => $this->frequency_hours,
        ]);

        $this->computeNextRun();

        session()->flash('message', 'Settings saved!');
    }

    public function toggle()
    {
        $this->is_active = !$this->is_active;

        $this->setting->update([
            'is_active' => $this->is_active
        ]);

        if ($this->is_active) {
            $this->startIrrigation();
        } else {
            $this->stopIrrigation();
        }
    }

    public function startIrrigation()
    {
        // TURN ON ESP32
        Http::get("http://192.168.1.100/on");

        // SAVE LAST RUN
        $this->setting->update([
            'last_run_at' => now()
        ]);

        // REFRESH LOCAL MODEL
        $this->setting->refresh();

        $this->computeNextRun();
    }

    public function stopIrrigation()
    {
        Http::get("http://192.168.1.100/off");
    }

    public function computeNextRun()
    {
        if ($this->setting->last_run_at) {

            $nextRun = $this->setting->last_run_at
                ->copy()
                ->addHours($this->frequency_hours);

            $this->nextRunTimestamp = $nextRun->timestamp;

        } else {
            // fallback: run immediately
            $this->nextRunTimestamp = now()->timestamp;
        }
    }

    public function render()
    {
        return view('livewire.timer.irrigation-timer-control');
    }
}