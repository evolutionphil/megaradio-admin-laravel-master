<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Station Requests') }}
        </h2>
    </x-slot>

    <livewire:admin.data-tables.station-requests-table />
</x-app-layout>

