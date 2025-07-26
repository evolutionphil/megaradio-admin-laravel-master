<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <livewire:admin.data-tables.users-table/>
</x-app-layout>

