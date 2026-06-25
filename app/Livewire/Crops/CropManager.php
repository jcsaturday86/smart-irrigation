<?php

namespace App\Livewire\Crops;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Crop;
use App\Models\IrrigationMode;
use App\Models\ActivityLog;

class CropManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $crop_name = '';
    public $moisture_min = '';
    public $moisture_max = '';
    public $state = '0';

    public $crop_id = null;
    public $search = '';

    protected function rules()
    {
        return [
            'crop_name' => 'required|string|max:200',
            'moisture_min' => 'required|numeric|gte:moisture_min',
            'moisture_max' => 'required|numeric|gte:moisture_max',
            //'moisture_max' => 'required|numeric',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {   
        $irrigationMode = IrrigationMode::find(1);
        $mode = $irrigationMode->mode;

        return view('livewire.crops.crop-manager', [
            'crops' => Crop::query()
                ->where('crop_name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(10),
            'activeCrop' => Crop::where('state', '1')->first(),
            'mode' => $mode
        ]);
    }

    public function resetForm()
    {
        $this->crop_name = '';
        $this->moisture_min = '';
        $this->moisture_max = '';
        $this->state = '0';
        $this->crop_id = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->crop_id) {
            $crop = Crop::findOrFail($this->crop_id);
        } else {
            $crop = new Crop();
            $crop->state = '0';
        }

        $crop->crop_name = $this->crop_name;
        $crop->moisture_min = $this->moisture_min;
        $crop->moisture_max = $this->moisture_max;
        $crop->state = $this->state ?? '0';
        $crop->save();

        ActivityLog::create([
            'action' => ($this->crop_id ? 'Crop updated: ' : 'Crop created: ') . $this->crop_name,
            'type' => 'crop',
        ]);

        session()->flash('message', $this->crop_id ? 'Updated successfully' : 'Created successfully');

        $this->resetForm();
    }

    public function edit($id)
    {
        $crop = Crop::findOrFail($id);

        $this->crop_id = $crop->id;
        $this->crop_name = $crop->crop_name;
        $this->moisture_min = $crop->moisture_min;
        $this->moisture_max = $crop->moisture_max;
        $this->state = $crop->state;
    }

    public function delete($id)
    {
        $crop = Crop::findOrFail($id);
        ActivityLog::create(['action' => 'Crop deleted: ' . $crop->crop_name, 'type' => 'crop']);
        $crop->delete();

        session()->flash('message', 'Deleted successfully');
    }

    public function setActive($id)
    {
        Crop::query()->update(['state' => '0']);
        Crop::where('id', $id)->update(['state' => '1']);

        $crop = Crop::find($id);
        ActivityLog::create(['action' => 'Crop set active: ' . $crop->crop_name, 'type' => 'crop']);

        session()->flash('message', 'Active crop updated.');
    }

    public function setInactive($id)
    {
        Crop::where('id', $id)->update(['state' => '0']);

        $crop = Crop::find($id);
        ActivityLog::create(['action' => 'Crop set inactive: ' . $crop->crop_name, 'type' => 'crop']);

        session()->flash('message', 'Active crop updated.');
    }
}