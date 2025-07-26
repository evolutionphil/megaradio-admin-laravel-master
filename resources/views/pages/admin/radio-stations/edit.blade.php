<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Edit Radio Station') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.radio-stations.update', $radioStation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-2 grid gap-6">
                <x-card>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')"/>

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $radioStation->name }}" required autofocus/>
                        </div>
                        <div>
                            <x-label for="homepage" :value="__('Homepage')"/>

                            <x-input id="homepage" class="block mt-1 w-full" type="text" name="homepage" value="{{ $radioStation->homepage }}"/>
                        </div>
                        <div>
                            <x-label>Genre</x-label>
                            <select name="tags[]" class="w-full rounded-md py-2" id="genre_select2" placeholder="Add tags..." multiple></select>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="url" :value="__('Url')"/>

                            <x-input id="url" class="block mt-1 w-full" type="url" name="url" value="{{ $radioStation->url }}"/>
                        </div>
                        <div>
                            <x-label for="url_resolved" :value="__('Url Resolved')"/>

                            <x-input id="url_resolved" class="block mt-1 w-full" type="url" name="url_resolved" value="{{ $radioStation->url_resolved }}"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center gap-2">
                            <input {{ $radioStation->popular ? 'checked' : '' }} id="popular" name="popular" type="checkbox"/>
                            <label for="popular">{{ __('Is Popular') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input {{ $radioStation->featured ? 'checked' : '' }} id="featured" name="featured" type="checkbox"/>
                            <label for="featured">{{ __('Is Featured') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input {{ $radioStation->is_working ? 'checked' : '' }} id="is_working" name="is_working" type="checkbox"/>
                            <label for="is_working">{{ __('Is Working') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input {{ $radioStation->is_global ? 'checked' : '' }} id="is_global" name="is_global" type="checkbox"/>
                            <label for="is_global">{{ __('Is Global') }}</label>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="favicon_url" :value="__('Favicon URL')"/>
                            <x-input id="favicon_url" class="block mt-1 w-full" type="url" name="favicon_url"/>
                        </div>
                        <div>
                            <x-label for="favicon_file" :value="__('Upload Favicon')"/>
                            <input id="favicon_file" class="block mt-1 w-full" type="file" name="favicon_file"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="country" :value="__('Country')"/>
                            <x-input-select-country selected="{{ $radioStation->country }}" id="country" class="block mt-1 w-full rounded" type="text" name="country"/>
                        </div>
                        <div>
                            <x-label for="state" :value="__('State')"/>

                            <x-input id="state" class="block mt-1 w-full" type="text" name="state" value="{{ $radioStation->state }}"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <x-label for="codec" :value="__('Codec')"/>

                            <x-input-select-codec :selected="$radioStation->codec" id="codec" class="block mt-1 w-full rounded" name="codec"/>
                        </div>
                        <div>
                            <x-label for="bitrate" :value="__('Bitrate')"/>

                            <x-input id="bitrate" class="block mt-1 w-full" type="text" name="bitrate" value="{{ $radioStation->bitrate }}"/>
                        </div>
                        <div class="flex items-center gap-2">
                            <input {{ $radioStation->hls ? 'checked' : '' }} id="hls" name="hls" type="checkbox"/>

                            <label for="hls">{{ __('Hls') }}</label>
                        </div>
                    </div>
                </x-card>

                <div class="flex justify-end items-end gap-4">
                    <x-select label="Source Description" id="source_description_lang">
                        @foreach ($languages as $language)
                            <option value="{{ $language->key }}">{{ $language->name }}</option>
                        @endforeach
                    </x-select>
                    <x-button type="button" onclick="autoTranslateDescription()">Translate Description</x-button>
                </div>

                <x-card>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase">
                        <tr>
                            <th scope="col" class="py-3">Language</th>
                            <th scope="col" class="py-3">Description</th>
                        </tr>
                        </thead>

                        @foreach ($languages as $language)
                            <tr>
                                <td>{{ $language->name }}</td>
                                <td>
                                    <x-input type="text" id="descriptions_{{ $language->key }}" name="descriptions[{{ $language->key }}]" value="{{ (!is_null($radioStation->descriptions)) ? (isset($radioStation->descriptions[$language->key]) ? $radioStation->descriptions[$language->key] : '') : '' }}"/>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </x-card>
            </div>

            <div class="col-span-1 space-y-4">
                <x-card>
                    <div>
                        <x-label for="logo" :value="__('Logo')"/>
                        <div class="flex justify-center items-center">
                            <img class="border center rounded" src="{{ $radioStation->favicon_url }}" alt="{{ $radioStation->name }}"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-buttons.play :station="$radioStation"/>
                </x-card>
            </div>
        </div>

        <div class="mt-4">
            <x-button class="py-2.5" type="submit">
                {{ __('Save') }}
            </x-button>

            <x-button class="py-2.5 bg-gray-600" type="button">
                {{ __('Cancel') }}
            </x-button>
        </div>
    </form>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet"/>
    @endpush


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
        <script>
            const tomSelectControl = new TomSelect('#genre_select2', {
                create: true,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                plugins: {
                    clear_button: {
                        title: 'Remove all selected options',
                    },
                    remove_button: {
                        title: 'Remove this item',
                    }
                },
                load: function (query, callback) {
                    const url = '/api/genres?searchQuery=' + encodeURIComponent(query);

                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json.data);
                        })
                },
                onInitialize: function () {
                    fetch('/api/genres?ids=' + '{{ collect($radioStation->genres)->map(fn($e) => (string) $e->oid)->join(',') }}')
                        .then((response) => response.json())
                        .then(data => {
                            data.data.forEach((item) => {
                                tomSelectControl.addOption({
                                    id: item.id,
                                    name: item.name
                                });
                            })
                            tomSelectControl.setValue(data.data.map(i => i.id));
                        })
                }
            });

            async function autoTranslateDescription() {
                const languages = @json($languages);

                const sourceDescriptionLang = document.getElementById('source_description_lang').value;

                const sourceDescription = document.getElementById(`descriptions_${sourceDescriptionLang}`).value;

                if (sourceDescription === '') {
                    alert('No description content found for selected language.');
                    return;
                }

                const overwriteExistingTranslations = confirm("Do you want to overwrite existing description?")

                for (const language of languages) {
                    if(language.key === sourceDescriptionLang) continue;

                    if (!overwriteExistingTranslations && document.getElementById(`descriptions_${language.key}`).value !== '') {
                        continue
                    }

                    try {
                        const {data} = await axios.post('{{ route('admin.languages.translate') }}', {
                            phrase: sourceDescription,
                            source_language: sourceDescriptionLang,
                            target_language: language.key
                        })

                        document.getElementById(`descriptions_${language.key}`).value = data
                    }catch (e) {
                        console.log(e)
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
