<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Radio Stations') }}
            </h2>

            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.radio-stations.sync') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Sync</a>
                <a href="{{ route('admin.radio-stations.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Add Radio Station</a>
            </div>
        </div>
    </x-slot>

    <livewire:admin.data-tables.radio-stations-table/>

    @push('scripts')
        <script src="{{ asset('js/player.js') }}"></script>
        <script>
            function playPause($audioSource, $buttonElement) {
                if ($audioSource.paused) {
                    $audioSource.play();
                    $buttonElement.textContent = "Stop";
                } else {
                    $audioSource.pause();
                    $buttonElement.textContent = "Play";
                }


                $audioSource.addEventListener("ended", function () {
                    $buttonElement.textContent = "Play";
                });
            }
        </script>
    @endpush
</x-app-layout>

