<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Similar Radio Stations for') }} <span class="font-bold">{{ $radioStation->name }}</span>
        </h2>
    </x-slot>

    <div class="flex flex-col gap-6" x-data>
        <div class="col-span-full">
            <div class="text-right">
                <x-link class="mr-3" href="{{ route('admin.radio-stations.edit', $radioStation->id) }}">Edit</x-link>

                @if($radioStation->deleted_at)
                    <x-button route="{{ route('admin.radio-stations.restore', $radioStation->id) }}">Restore</x-button>
                @else
                    <x-buttons.delete route="{{ route('admin.radio-stations.destroy', $radioStation->id) }}">Delete</x-buttons.delete>
                @endif
            </div>
        </div>

        <div class="col-span-2 grid gap-6">
            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6">
                    <div>
                        <x-label class="font-bold" for="id" :value="__('Favicon')"/>

                        <img src="{{ $radioStation->favicon }}" alt="{{ $radioStation->name }}" class="w-20 h-20 object-cover rounded-lg"/>
                    </div>
                    <div>
                        <x-label class="font-bold" for="name" :value="__('Name')"/>

                        <h2 class="break-all">{{ $radioStation->name }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="homepage" :value="__('Homepage')"/>

                        <h2 class="break-all">{{ $radioStation->homepage }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="tags" :value="__('Genre')"/>
                        <h2 class="break-all">
                            @foreach(explode(',', $radioStation->tags) as $tag)
                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ $tag }}</span>
                            @endforeach
                    </div>

                    <div>
                        <x-label class="font-bold" for="url" :value="__('Url')"/>

                        <h2 class="break-all truncate">{{ $radioStation->url }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="url_resolved" :value="__('Url Resolved')"/>

                        <h2 class="break-all truncate">{{ $radioStation->url_resolved }}</h2>
                    </div>

                    <div>
                        <x-label class="font-bold" for="country" :value="__('Country')"/>

                        <h2 class="break-all">{{ $radioStation->country }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="countrycode" :value="__('Country Code')"/>

                        <h2 class="break-all">{{ $radioStation->countrycode }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="state" :value="__('State')"/>

                        <h2 class="break-all">{{ $radioStation->state }}</h2>
                    </div>

                    <div>
                        <x-label class="font-bold" for="codec" :value="__('Codec')"/>

                        <h2 class="break-all">{{ $radioStation->codec }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="bitrate" :value="__('Bitrate')"/>

                        <h2 class="break-all">{{ $radioStation->bitrate }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="hls" :value="__('Hls')"/>

                        <h2 class="break-all">{{ $radioStation->hls }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="bitrate" :value="__('Votes')"/>

                        <h2 class="break-all">{{ $radioStation->votes ?? 0 }}</h2>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <h2 class="text-lg font-semibold text-gray-900 pb-4">{{ __('Linked Stations') }}</h2>
                </h2>
                <livewire:admin.data-tables.linked-stations :station="$radioStation" />
            </x-card>

            <x-card>
                <h2 class="text-lg font-semibold text-gray-900 pb-4">{{ __('Suggested Stations') }}</h2>

                <livewire:admin.data-tables.suggested-stations :station="$radioStation" />
            </x-card>
        </div>
    </div>
</x-app-layout>
