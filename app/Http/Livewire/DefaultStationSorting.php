<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class DefaultStationSorting extends Component
{
    public $newSortingField = '';

    public $sortingMode = 'asc';

    public $availableFields = [
        'has_uploaded_favicon' => 'Has Uploaded Favicon',
        'votes' => 'Votes',
        'clickcount' => 'Click Count',
    ];

    public $defaultStationSorting = [];

    public function render()
    {
        $this->defaultStationSorting = $this->getDefaultSorting();

        return view('livewire.default-station-sorting');
    }

    private function getDefaultSorting()
    {
        $value = Setting::where('name', 'api::default_station_sorting')->first()->value ?? [];

        return json_decode($value, true);
    }

    private function updateDefaultSorting()
    {
        Setting::updateOrCreate(
            ['name' => 'api::default_station_sorting'],
            ['value' => json_encode($this->defaultStationSorting)]
        );
    }

    public function deleteSortField($fieldName)
    {
        unset($this->defaultStationSorting[$fieldName]);

        $this->updateDefaultSorting();
    }

    public function addNewSortField()
    {
        $this->validate([
            'newSortingField' => 'required',
            'sortingMode' => 'required',
        ], [
            'newSortingField.required' => 'The field name is required.',
            'sortingMode.required' => 'The sorting mode is required.',
        ]);

        $this->defaultStationSorting[$this->newSortingField] = $this->sortingMode;

        $this->updateDefaultSorting();
    }

    public function changeSortingMode($fieldName, $sortingMode)
    {
        $this->defaultStationSorting[$fieldName] = $sortingMode;

        $this->updateDefaultSorting();
    }

    public function changeOrder($fieldName, $newIndex)
    {
        $defaultStationSorting = $this->defaultStationSorting;

        $keys = array_keys($defaultStationSorting);

        $currentIndex = array_search($fieldName, $keys);

        $keys[$currentIndex] = $keys[$newIndex];
        $keys[$newIndex] = $fieldName;

        $this->defaultStationSorting = collect($keys)
            ->flatMap(function ($key) use ($defaultStationSorting) {
                return [$key => $defaultStationSorting[$key]];
            })->toArray();

        $this->updateDefaultSorting();
    }
}
