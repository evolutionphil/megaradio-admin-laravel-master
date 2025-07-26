<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ads') }}
        </h2>
    </x-slot>

    <form action="{{ route('admin.ads.update', $ads) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-label for="name" :value="__('Name')"/>

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                 autofocus/>
                    </div>
                    <div>
                        <x-label for="url" :value="__('Ads URL')"/>

                        <x-input id="url" class="block mt-1 w-full" type="url" name="url" required
                                 autofocus/>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-label>Ads Image</x-label>
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
