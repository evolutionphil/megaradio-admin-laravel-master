<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Radio Station') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.radio-stations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-2 grid gap-6">
                <x-card>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Name')"/>

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" autofocus required/>
                        </div>
                        <div>
                            <x-label for="homepage" :value="__('Homepage')"/>

                            <x-input id="homepage" class="block mt-1 w-full" type="url" name="homepage" required/>
                        </div>
                        <div>
                            <x-label>Genre</x-label>
                            <select name="tags[]" class="w-full rounded-md py-2" id="genre_select2" multiple placeholder="Add tags..."></select>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="url" :value="__('Url')"/>
                            <x-input id="url" class="block mt-1 w-full" type="url" name="url"/>
                        </div>
                        <div>
                            <x-label for="url_resolved" :value="__('Url Resolved')"/>
                            <x-input id="url_resolved" class="block mt-1 w-full" type="url" name="url_resolved"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="favicon_file" :value="__('Favicon')"/>

                            <input id="favicon_file" class="block mt-1 w-full" type="file" name="favicon_file"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-label for="country" :value="__('Country')"/>

                            <x-input-select-country id="country" class="block mt-1 w-full rounded" type="text" name="country"/>
                        </div>
                        <div>
                            <x-label for="state" :value="__('State')"/>

                            <x-input id="state" class="block mt-1 w-full" type="text" name="state"/>
                        </div>
                        <div>
                            <x-label for="language" :value="__('Language')"/>

                            <x-input id="language" class="block mt-1 w-full" type="text" name="language"/>
                        </div>

                        <hr class="col-span-full">

                        <div>
                            <x-label for="geo_lat" :value="__('Latitude')"/>

                            <x-input step="0.000001" id="geo_lat" class="block mt-1 w-full" type="number" name="geo_lat"/>
                        </div>
                        <div>
                            <x-label for="geo_long" :value="__('Longitude')"/>

                            <x-input step="0.000001" id="geo_long" class="block mt-1 w-full" type="number" name="geo_long"/>
                        </div>
                    </div>
                </x-card>
                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center gap-2">
                            <input id="popular" name="popular" type="checkbox"/>
                            <label for="popular">{{ __('Is Popular') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="featured" name="featured" type="checkbox"/>
                            <label for="featured">{{ __('Is Featured') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="is_working" name="is_working" type="checkbox"/>
                            <label for="is_working">{{ __('Is Working') }}</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="is_global" name="is_global" type="checkbox"/>
                            <label for="is_global">{{ __('Is Global') }}</label>
                        </div>
                    </div>
                </x-card>
                <x-card>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <x-label for="codec" :value="__('Codec')"/>

                            <x-input-select-codec id="codec" class="block mt-1 w-full rounded" name="codec"/>
                        </div>
                        <div>
                            <x-label for="bitrate" :value="__('Bitrate')"/>

                            <x-input id="bitrate" class="block mt-1 w-full" type="text" name="bitrate"/>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="hls" name="hls" type="checkbox"/>

                            <label for="hls">{{ __('Hls') }}</label>
                        </div>
                    </div>
                </x-card>

            </div>

            <div class="col-span-1">

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
            new TomSelect('#genre_select2', {
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
                }
            });
        </script>
    @endpush
</x-app-layout>
