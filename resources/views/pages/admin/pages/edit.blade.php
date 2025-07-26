<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update') }} {{ $page->name }}
        </h2>
    </x-slot>

    <div x-data="{showModal: false}">
        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="-mx-4 mb-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 rounded-lg bg-white">
                <div class="px-4 py-5">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ $page->name }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>

            <div class="-mx-4 mb-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 rounded-lg bg-white">
                <div class="px-4 py-3.5 text-left text-sm font-bold text-gray-900 border-b flex justify-between">
                    <div class="flex justify-between items-center w-full">
                        <h4 class="text-xl">Contents</h4>
                        <div>
                            <x-button type="button" @click="showModal = true">
                                Add Translation
                            </x-button>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-5">
                    <div class="gap-6 items-center">
                        @foreach ($page->getTranslations('contents') as $lang => $content)
                            <div class="col-span-full md:col-span-3">
                                <label for="contents[{{$lang}}]" class="block text-lg font-medium text-gray-700">
                                    Site Description({{ $lang }})
                                </label>
                                <x-trix-field name="contents[{{$lang}}]" id="contents[{{$lang}}]" value="{!! $content !!}"/>
                            </div>

                            <div class="my-6 h-1 border-b"></div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-right py-4">
                <x-button>Save Changes</x-button>
            </div>
        </form>
    </div>

    <div class="fixed z-10 inset-0 overflow-y-auto" x-cloak aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="showModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-[800px] w-full sm:p-6">
                <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                    <button @click="showModal = false" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Translation</h3>
                        <div class="mt-2">
                            <form action="{{ route('admin.pages.translations.store', $page->id) }}" id="form-new-translation" method="post" class="grid grid-cols-1 gap-6">
                                @csrf
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700">Language</label>
                                    <select id="location" name="language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->key }}">{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700">Content</label>
                                    <x-trix-field name="content" id="translation[content]"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="form-new-translation" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Deactivate</button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
