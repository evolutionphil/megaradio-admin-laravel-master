<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Radio Station') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-full">
            <div class="text-right">
                <x-link class="mr-3" href="{{ route('admin.radio-stations.edit', $radioStation->id) }}">Edit</x-link>

                <x-buttons.delete route="{{ route('admin.radio-stations.destroy', $radioStation->id) }}">Delete</x-buttons.delete>
            </div>
        </div>

        <div class="col-span-2 grid gap-6">
            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>
            </x-card>

            <x-card>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <x-label class="font-bold" for="url" :value="__('Url')"/>

                        <h2 class="break-all">{{ $radioStation->url }}</h2>
                    </div>
                    <div>
                        <x-label class="font-bold" for="url_resolved" :value="__('Url Resolved')"/>

                        <h2 class="break-all">{{ $radioStation->url_resolved }}</h2>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
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
                </div>
            </x-card>

            <x-card>
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
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
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <x-card>
                <div>
                    <x-label for="logo" :value="__('Logo')"/>
                    <div class="flex justify-center items-center">
                        <img class="border center rounded" src="{{ $radioStation->favicon_url }}" alt="{{ $radioStation->name }}"/>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
