<x-card>
    <div class="pb-4 flex justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Default Sorting For Stations</h2>
            <small class="text-gray-500">Changes are immediately applied</small>
        </div>

        <button type="button" x-on:click="$dispatch('open-modal', 'add-new-field')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                <path d="M12 5v14M5 12h14"/>
            </svg>
        </button>
    </div>

    <div x-data="{ handle: (item, position) => { $wire.changeOrder(item, position) } }">
        <ul x-sort="handle" class="space-y-2 w-full">
            @foreach($defaultStationSorting as $field => $value)
                <x-settings.sort-item x-sort:item="`{{ $field }}`">
                    <div class="flex gap-2 justify-between items-center w-full">
                        <p>{{ $availableFields[$field] }}</p>
                        <div class="flex gap-4">
                            <x-select x-on:change="$wire.changeSortingMode(`{{ $field }}`, event.target.value)" id="default_station_sorting[{{ $field }}]" name="default_station_sorting[{{ $field }}]" value="{{ $value }}">
                                <option value="asc" {{$value == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{$value == 'desc' ? 'selected' : '' }}>Descending</option>
                            </x-select>
                            <button type="button" class="text-red-600" wire:click="deleteSortField('{{ $field }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </x-settings.sort-item>
            @endforeach
        </ul>
    </div>

    <x-modal name="add-new-field" title="Add New Field">
        <x-slot:content>
            <div>
                <form wire:submit.prevent="addNewSortField" id="addNewSortField" method="post">
                    <div class="space-y-4 w-full">
                        <div class="flex flex-col">
                            <label for="field">
                                Field
                            </label>
                            <x-select id="newSortingField" wire:model="newSortingField" class="w-full">
                                <option>-- Select Option --</option>
                                @foreach($availableFields as $key => $field)
                                    <option value="{{ $key }}">{{ $field }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="flex flex-col">
                            <label for="sortingMode">Sorting Mode</label>
                            <x-select id="sortingMode" wire:model="sortingMode" class="w-full">
                                <option>-- Select Option --</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </x-select>
                        </div>

                        <div class="flex justify-end">
                            <x-button formtarget="addNewSortField">Save</x-button>
                        </div>
                    </div>
                </form>
            </div>
        </x-slot:content>
    </x-modal>
</x-card>
