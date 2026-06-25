<?php

namespace App\Livewire\Manual;

use Livewire\Component;
use App\Models\ManualIrrigationSettings;
use App\Models\IrrigationMode;
use App\Models\ActivityLog;

class ApiManualIrrigationControl extends Component
{

    public $status;

    public function mount()
    {
        $this->status = ManualIrrigationSettings::where('action', 'manual_irrigation')->value('value') ?? 'OFF';
    }

    public function toggle()
    {
        $this->status = $this->status === 'ON' ? 'OFF' : 'ON';
        ManualIrrigationSettings::updateOrCreate(
            ['action' => 'manual_irrigation'],
            ['value' => $this->status]
        );

        ActivityLog::create(['action' => 'Irrigation turned ' . $this->status, 'type' => 'irrigation']);
    }

    public function render()
    {
        $irrigationMode = IrrigationMode::find(1);
        $mode = $irrigationMode->mode;

        return view('livewire.manual.api-manual-irrigation-control',['mode'=>$mode]);
    }
}
