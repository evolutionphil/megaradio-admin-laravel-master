<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <form class="" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <x-card>
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Radio Sync</h2>
                </div>
                <ul role="list" class="mt-2 divide-y divide-gray-200">
                    <li class="py-4 flex items-center justify-between">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-900" id="privacy-option-4-label">Allow Automatic Sync</p>
                        </div>
                        <x-switch name="radio-browser::is_sync_enabled" value="{{ $settings['radio-browser::is_sync_enabled'] }}"/>
                    </li>
                    <li class="py-4 flex items-center justify-between">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-gray-900" id="privacy-option-4-label">Last synced at</p>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-sm text-gray-600" id="privacy-option-4-label">{{ \Carbon\Carbon::parse($settings['radio-browser::last_sync_started_at'])->diffForHumans() }}</p>
                        </div>
                    </li>
                </ul>
            </x-card>

            <x-card>
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Social Links</h2>
                </div>
                <ul role="list" class="mt-2 divide-y divide-gray-200">
                    <li class="py-4 flex items-center justify-between">
                        <x-label>Facebook</x-label>
                        <x-input type="text" class="max-w-max" name="social-links::facebook" value="{{ $settings['social-links::facebook'] }}"/>
                    </li>
                    <li class="py-4 flex items-center justify-between">
                        <x-label>Instagram</x-label>
                        <x-input type="text" class="max-w-max" name="social-links::instagram" value="{{ $settings['social-links::instagram'] }}"/>
                    </li>
                    <li class="py-4 flex items-center justify-between">
                        <x-label>Twitter</x-label>
                        <x-input type="text" class="max-w-max" name="social-links::twitter" value="{{ $settings['social-links::twitter'] }}"/>
                    </li>
                </ul>
            </x-card>


            <x-card>
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Site Settings</h2>
                </div>
                <ul role="list" class="mt-2 divide-y divide-gray-200">
                    @if (isset($settings['site-settings::google_adsense_code']))
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Google Adsense Code</x-label>
                            <x-input type="text" class="max-w-max" name="site-settings::google_adsense_code" value="{{ $settings['site-settings::google_adsense_code'] }}"/>
                        </li>
                    @endif

                    @if (isset($settings['site-settings::enable_uncategorized_genre']))
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Enable Uncategoriged Genre</x-label>
                            <x-switch name="site-settings::enable_uncategorized_genre" value="{{ $settings['site-settings::enable_uncategorized_genre'] }}"/>
                        </li>
                    @endif
                </ul>
            </x-card>

            <div class="flex justify-end">
                <x-button>Save Changes</x-button>
            </div>
        </div>
    </form>
</x-app-layout>
