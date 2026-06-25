<?php

namespace App\Livewire\Manual;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ManualIrrigationControl extends Component
{
    public $is_running = false;
    public $statusMessage = 'System Idle';
    public $deviceStatus = 'Checking...'; // ONLINE / OFFLINE

    protected $esp32Url = "http://192.168.1.231";

    public function mount()
    {
        $this->checkDeviceStatus();
    }

    public function start()
    {
        try {
            Http::timeout(3)->get($this->esp32Url . "/on");

            $this->is_running = true;
            $this->statusMessage = 'Irrigation Started 🌱';

            session()->flash('message', 'Manual irrigation started!');
        } catch (\Exception $e) {
            $this->deviceStatus = 'OFFLINE';
            session()->flash('message', 'ESP32 is offline!');
        }
    }

    public function stop()
    {
        try {
            Http::timeout(3)->get($this->esp32Url . "/off");

            $this->is_running = false;
            $this->statusMessage = 'Irrigation Stopped ⛔';

            session()->flash('message', 'Manual irrigation stopped!');
        } catch (\Exception $e) {
            $this->deviceStatus = 'OFFLINE';
            session()->flash('message', 'ESP32 is offline!');
        }
    }

    public function checkDeviceStatus()
    {
        try {
            Http::timeout(2)->get($this->esp32Url);

            $this->deviceStatus = 'ONLINE';
        } catch (\Exception $e) {
            $this->deviceStatus = 'OFFLINE';
        }
    }

    public function render()
    {
        return view('livewire.manual.manual-irrigation-control');
    }
}