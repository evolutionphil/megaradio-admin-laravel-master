<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Languages') }}
        </h2>
    </x-slot>

    <div class="mb-4">
        <div class="shadow overflow-hidden sm:rounded-md">
            <div class="py-3 px-4 bg-gray-50 flex justify-between">
                <h1 class="text-xl">Import Translations</h1>
            </div>

            <div class="px-4 py-5 bg-white sm:p-6">
                <small class="text-gray-400">Hint: Translations will be imported only for existing languages. See sample csv file below.</small>
                <form action="{{ route('admin.languages.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="import_file" id="">
                    <x-button>Import</x-button>
                </form>
                <a href="{{ route('admin.download-language-import-sample') }}" class="text-blue-400">Sample file</a>
            </div>
        </div>
    </div>

    <div class="my-10 sm:mt-0">
        <form action="{{ route('admin.languages.phrases.store') }}" method="POST">
            @csrf
            <div class="shadow overflow-hidden sm:rounded-md border">
                <div class="py-3 px-4 bg-gray-50 flex justify-between">
                    <h1 class="text-xl">Add new phrase</h1>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="w-full md:w-2/3 my-4">
                        <label for="phrase_key" class="block text-sm font-medium text-gray-700">Phrase
                            Key</label>
                        <input type="text" name="key" id="phrase_key" autocomplete="address-level2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="grid grid-cols-6 gap-6">
                        @foreach($languages as $lang)
                            <div class="col-span-3 sm:col-span-3 lg:col-span-2">
                                <label for="input_{{ $lang->key }}" class="block text-sm font-medium text-gray-700">
                                    {{ $lang->name }}
                                    Translation
                                </label>
                                <textarea id="input_{{ $lang->key }}" name="phrases[{{ $lang->key }}]" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="my-10 sm:mt-0">
        <form action="{{ route('admin.languages.store') }}" method="POST">
            @csrf
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="py-3 px-4 bg-gray-50">
                    <h1 class="text-xl">Add new language</h1>
                </div>

                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Language
                                Name</label>
                            <input type="text" name="name" id="name" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label for="key" class="block text-sm font-medium text-gray-700">Language
                                Key</label>
                            <input type="text" name="key" id="key" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                            <label for="is_rtl" class="hidden text-sm font-medium text-gray-700">Is
                                RTL?</label>
                            <select id="is_rtl" name="is_rtl" autocomplete="is_rtl" class="mt-1 hidden w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option class="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Languages</h1>
        </div>
    </div>

    <div>
        <livewire:admin.data-tables.languages-table/>
    </div>
</x-app-layout>
