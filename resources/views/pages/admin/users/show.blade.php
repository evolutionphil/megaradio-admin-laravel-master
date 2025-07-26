<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View User') }}
        </h2>
    </x-slot>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-label class="font-bold" for="name" :value="__('Name')"/>

                <h2 class="break-all">{{ $user->name }}</h2>
            </div>
            <div>
                <x-label class="font-bold" for="Email" :value="__('Email')"/>

                <h2 class="break-all">{{ $user->email }}</h2>
            </div>
            <div>
                <x-label class="font-bold" for="email_verified_at" :value="__('Is Email Verified')"/>
                <p>{{ is_null($user->email_verified_at) ? 'No' : 'Yes' }}</p>
            </div>
            <div>
                <x-label class="font-bold" for="country" :value="__('Country')"/>

                <h2 class="break-all">{{ $user->country }}</h2>
            </div>
            <div>
                <x-label class="font-bold" for="language" :value="__('Language')"/>

                <h2 class="break-all">{{ $user->language }}</h2>
            </div>
            <div>
                <x-label class="font-bold" for="email_verified_at" :value="__('Is Public')"/>
                <p>{{ $user->is_public_profile ? 'Yes' : 'No' }}</p>
            </div>
        </div>
    </x-card>

    <div class="mt-4">
        <h2 class="text-2xl pb-4">Favorite Stations</h2>
        <x-table
            :columns='[
                        [
                          "name" => "Name",
                          "field" => "name",
                        ]
                      ]'

            :rows='$favoriteStations'
        >
            <x-slot name="tableActions">
                <div class="flex flex-wrap space-x-4">
                    <button class="text-blue-500" @click="$store.radioPlayer.playRadio(row)">Play</button>
                    <a :href="`/admin/radio-stations/${row.id}`" class="text-blue-500">Show</a>
                </div>
            </x-slot>
        </x-table>
    </div>
</x-app-layout>

