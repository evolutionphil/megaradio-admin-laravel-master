<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Phrases') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.languages.auto-translate', $language->id) }}" method="post" id="auto_translate_form">
        @csrf
        <input type="hidden" name="auto_translate" value="true">
    </form>

    <form action="{{ route('admin.languages.update', $language->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="text-right py-4">
            <x-button onclick="handleAutoTranslate()" type="button" class="bg-blue-700">Auto Translate</x-button>
            <x-button>Save Changes</x-button>
        </div>
        <div class="-mx-4 mb-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 rounded-lg bg-white">
            <div class="px-4 py-5">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 justify-start items-center">
                    <div class="">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Language Name
                        </label>
                        <x-input type="text" name="name" id="name" value="{{ $language->name }}" required />
                    </div>

                    <div class="">
                        <label for="key" class="block text-sm font-medium text-gray-700">Language
                            Key</label>
                        <x-input type="text" name="key" id="key" value="{{ $language->key }}" required />
                    </div>

                    <div class="">
                        <label for="key" class="block text-sm font-medium text-gray-700">ISO Code</label>
                        <x-input
                            type="text"
                            name="iso"
                            id="iso"
                            value="{{ $language->iso }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="en-US"
                        />
                    </div>
                    <div class="flex flex-col gap-4">
                        <label for="is_published" class="text-sm font-medium text-gray-700">Is Published?</label>
                        <x-switch name="is_published" value="{{ $language->is_published }}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="-mx-4 mb-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 rounded-lg bg-white">
            <div class="px-4 py-3.5 text-left text-sm font-bold text-gray-900 border-b flex justify-between">
                <div>
                    Meta Info
                </div>

                <div>
                    <abbr class="cursor-pointer flex justify-center items-center rounded-full text-white p-2 h-6 w-6 bg-blue-400" title="To include station name use this: {STATION_NAME}">?</abbr>
                </div>
            </div>

            <div class="px-4 py-5">
                <div class="grid grid-cols-7 gap-2 md:gap-6 items-center">
                    @foreach ($pages as $page)
                        <div class="col-span-full md:col-span-1">
                            <h4 class="font-bold">{{ Str::title(str_replace('_', ' ', $page)) }}</h4>
                        </div>
                        <div class="col-span-full md:col-span-3">
                            <label for="site_meta[{{$page}}][title]" class="block text-sm font-medium text-gray-700">
                                Site Title
                            </label>
                            <x-input type="text" name="site_meta[{{$page}}][title]" id="site_meta[{{$page}}][title]" value="{{ getPageMetaValue($language->site_meta, $page, 'title') }}"/>
                            @if ($errors->has('site_meta.'. $page .'.title'))
                                <p class="mt-2 text-sm text-red-600">
                                    @error('site_meta.'. $page .'.title')
                                    {{ $message }}
                                    @enderror
                                </p>
                            @endif
                        </div>

                        <div class="col-span-full md:col-span-3">
                            <label for="site_meta[{{$page}}][description]" class="block text-sm font-medium text-gray-700">
                                Site Description
                            </label>
                            <x-input type="text" name="site_meta[{{$page}}][description]" id="site_meta[{{$page}}][description]" value="{{ getPageMetaValue($language->site_meta, $page, 'description') }}"/>
                            @if ($errors->has('site_meta.'. $page .'.description'))
                                <p class="mt-2 text-sm text-red-600">
                                    @error('site_meta.'. $page .'.description')
                                    {{ $message }}
                                    @enderror
                                </p>
                            @endif
                        </div>

                        <!-- <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                <label for="site_meta[{{$page}}][keywords]" class="block text-sm font-medium text-gray-700">
                                    Site Keywords
                                </label>
                                <x-input type="text" name="site_meta[{{$page}}][keywords]" id="site_meta[{{$page}}][keywords]" value="{{ getPageMetaValue($language->site_meta, $page, 'keywords') }}" />
                            </div> -->
                    @endforeach
                </div>
            </div>
        </div>

        <div class="-mx-4 ring-1 ring-gray-300 sm:-mx-6 md:mx-0 rounded-lg bg-white overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 overflow-x-auto">
                <thead>
                <tr>
                    <th scope="col" class="py-3.5 w-10 pl-4 pr-3 text-left text-sm font-bold text-gray-900 sm:pl-6">
                        Phrase Keys
                    </th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold text-gray-900 sm:pl-6">
                        English Translation
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold text-gray-900 table-cell">
                        Translation
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($language->phrases as $key => $value)
                    <tr>
                        <td class="px-3 py-3.5 text-sm text-black table-cell">{{ $key }}</td>

                        <td class="px-3 py-3.5 text-sm text-black table-cell">
                            <x-textarea disabled class="w-full" name="translations_source[{{ $key }}]" type="text">{{ $englishTranslations[$key] }}</x-textarea>
                        </td>

                        <td class="px-3 py-3.5 text-sm text-black gap-2 flex w-full">
                            <x-textarea class="flex-1 w-full" name="translations[{{ $key }}]" type="text">{{ $value }}</x-textarea>
                            <button type="button" class="bg-blue-500 px-2 flex-grow-0 text-white rounded" onclick="translatePhrase('{{ $key }}')">Translate</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right py-4">
            <x-button>Save Changes</x-button>
        </div>
    </form>
</x-app-layout>

<script>
function handleAutoTranslate() {
    const accepted = confirm('This action will auto translate all site meta & phrases based on English value. Do you want to continue?');

    if (accepted) {
        document.forms.auto_translate_form.submit()
    }
}

async function translatePhrase(key) {
    const sourcePhrase = document.querySelector(`textarea[name="translations_source[${key}]"]`).value;

    const {data} = await axios.post('{{ route('admin.languages.translate') }}', {
        phrase: sourcePhrase,
        target_language: '{{$language->key}}'
    })

    document.querySelector(`textarea[name="translations[${key}]"]`).value = data;
}
</script>
