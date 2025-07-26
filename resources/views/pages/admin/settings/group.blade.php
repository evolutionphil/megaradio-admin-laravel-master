<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Settings') }}
        </h2>
    </x-slot>

    <div class="grid md:grid-cols-2 gap-4" x-data>
        <livewire:default-station-sorting />
    </div>
</x-app-layout>
