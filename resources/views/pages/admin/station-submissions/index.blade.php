<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Station Submissions') }}
        </h2>
    </x-slot>

    <livewire:admin.data-tables.station-submissions-table />
</x-app-layout>

