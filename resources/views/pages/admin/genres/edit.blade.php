<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Genre') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-label for="name" :value="__('Name')"/>

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$genre->name" required autofocus/>
                    </div>

                    <div>
                        <x-label>Background Image</x-label>
                        @if($genre->image)
                            <img class="border-2 rounded" src="{{ getImageUrl($genre->image) }}" alt="Image">
                        @endif
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-label for="is_discoverable" :value="__('Is Discoverable?')"/>

                        <x-switch class="my-3" id="is_discoverable" name="is_discoverable" :value="$genre->is_discoverable"/>

                    </div>
                    <div>
                        <x-label for="discoverable_label" :value="__('Discoverable Label')"/>

                        <x-input id="discoverable_label" class="block mt-1 w-full" type="text" name="discoverable_label" :value="$genre->discoverable_label"/>

                    </div>
                    <div>
                        <x-label>Background Image: <span class="text-sm text-yellow-600">(595x215)px</span></x-label>
                        <div class="flex">
                            <div>
                                <input type="file" name="image" class="block">
                            </div>
                            <div class="flex items-center">
                                <p class="mr-1">Or</p>
                                <x-input name="image_url" class="block w-full" type="text" placeholder="Image URL"/>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>


        <div class="mt-4">
            <x-button type="submit" class="py-2.5">Submit</x-button>
        </div>
    </form>
</x-app-layout>
