<!-- Responsive Navigation Menu -->
<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.ads.index')" :active="request()->routeIs('admin.ads.*')">
            {{ __('Ads') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.feedbacks.index')" :active="request()->routeIs('admin.feedbacks.*')">
            {{ __('Feedbacks') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.genres.index')" :active="request()->routeIs('admin.genres.*')">
            {{ __('Genres') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.languages.index')" :active="request()->routeIs('admin.languages.*')">
            {{ __('Languages') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.pages.index')" :active="request()->routeIs('admin.pages.*')">
            {{ __('Pages') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.radio-stations.index')" :active="request()->routeIs('admin.radio-stations.*')">
            {{ __('Radio Stations') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.station-requests.index')" :active="request()->routeIs('admin.station-requests.*')">
            {{ __('Station Requests') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.station-submissions.index')" :active="request()->routeIs('admin.station-submissions.*')">
            {{ __('Station Submissions') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.settings.show')" :active="request()->routeIs('admin.settings.show')">
            {{ __('Site Settings') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.settings.group')" :active="request()->routeIs('admin.settings.group')">
            {{ __('API Settings') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            {{ __('Users') }}
        </x-responsive-nav-link>

    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.edit')">
                {{ __('Edit Profile') }}
            </x-responsive-nav-link>
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>
