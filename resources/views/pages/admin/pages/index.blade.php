<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <!-- <div class="sm:flex sm:items-center py-4">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Radio Stations</h1>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Create Page</a>
        </div>
    </div> -->

    <livewire:admin.data-tables.pages-table/>
</x-app-layout>
