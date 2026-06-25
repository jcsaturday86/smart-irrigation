<?php

namespace App\Livewire\Timer;

use Livewire\Component;
use App\Models\IrrigationTimerSetting;
use App\Models\IrrigationMode;
use App\Models\ActivityLog;
use Carbon\Carbon;

class TimerIrrigation extends Component
{
    public $duration_minutes = 1;
    public $frequency_hours = 1;
    public $status = 'Stopped';
    public $nextIrrigationIn = 'Not scheduled';
    public $last_run_at = null;
    public $state = 'Inactive';

    public function mount()
    {
        $this->loadTimer();
    }

    public function getOrCreateTimer()
    {
        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer) {
            $timer = IrrigationTimerSetting::create([
                'duration_minutes' => 1,
                'frequency_hours' => 1,
                'is_active' => false,
                'state' => 'Inactive',
                'last_run_at' => null,
            ]);
        }

        return $timer;
    }

    public function loadTimer()
    {
        $timer = $this->getOrCreateTimer();

        $this->duration_minutes = $timer->duration_minutes;
        $this->frequency_hours = $timer->frequency_hours;
        $this->status = $timer->is_active ? 'Running' : 'Stopped';
        $this->state = $timer->state;

        $this->last_run_at = $timer->last_run_at
            ? Carbon::parse($timer->last_run_at)->format('M d, Y h:i A')
            : '—';

        if ($timer->is_active) {
            if ($timer->last_run_at) {
                //$nextRun = Carbon::parse($timer->last_run_at)->addHours($timer->frequency_hours);
                $nextRun = Carbon::parse($timer->last_run_at)->addMinutes($timer->frequency_hours);

                $this->nextIrrigationIn = $nextRun->isPast()
                    ? 'Running now...'
                    : $nextRun->diffForHumans();
            } else {
                $this->nextIrrigationIn = 'Running now...';
            }
        } else {
            $this->nextIrrigationIn = 'Not scheduled';
        }
    }

    public function saveSettings()
    {
        $this->validate([
            'duration_minutes' => 'required|integer|min:1|max:180',
            'frequency_hours' => 'required|integer|min:1|max:24',
        ]);

        $timer = $this->getOrCreateTimer();

        $timer->duration_minutes = $this->duration_minutes;
        $timer->frequency_hours = $this->frequency_hours;
        $timer->save();

        ActivityLog::create(['action' => 'Timer settings saved (' . $this->duration_minutes . 'min / ' . $this->frequency_hours . 'hr)', 'type' => 'timer']);

        session()->flash('message', 'Timer settings saved successfully.');
        $this->loadTimer();
    }

    public function startIrrigationNow()
    {
        $this->validate([
            'duration_minutes' => 'required|integer|min:1|max:180',
            'frequency_hours' => 'required|integer|min:1|max:24',
        ]);

        $timer = $this->getOrCreateTimer();

        $timer->duration_minutes = $this->duration_minutes;
        $timer->frequency_hours = $this->frequency_hours;
        $timer->is_active = true;
        $timer->state = 'Active';

        // Make the timer due immediately on next API poll
        $timer->last_run_at = now()->subMinutes($this->frequency_hours);

        $timer->save();

        ActivityLog::create(['action' => 'Timer irrigation started', 'type' => 'timer']);

        session()->flash('message', 'Irrigation started now.');
        $this->loadTimer();
    }

    public function stopIrrigation()
    {
        $timer = $this->getOrCreateTimer();

        $timer->is_active = false;
        $timer->state = 'Inactive';
        $timer->save();

        ActivityLog::create(['action' => 'Timer irrigation stopped', 'type' => 'timer']);

        session()->flash('message', 'Irrigation stopped.');
        $this->loadTimer();
    }

    public function render()
    {
        $this->loadTimer();

        $timers = IrrigationTimerSetting::latest()->get();
        $irrigationMode = IrrigationMode::find(1);
        $mode = $irrigationMode?->mode;

        return view('livewire.timer.timer-irrigation', [
            'timers' => $timers,
            'mode' => $mode
        ]);
    }
}