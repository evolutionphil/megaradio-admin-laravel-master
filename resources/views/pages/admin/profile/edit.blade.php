<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form class="" action="{{ route('admin.profile.edit') }}" method="POST">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <x-card>
                    <div>
                        <h2 class="text-lg leading-6 font-medium text-gray-900">Edit Profile</h2>
                    </div>
                    <ul role="list" class="mt-2 divide-y divide-gray-200">
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Name</x-label>
                            <x-input class="min-w-max" type="text" name="name" value="{{ $user->name }}"/>
                        </li>
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Email</x-label>
                            <x-input class="min-w-max" type="email" name="email" value="{{ $user->email }}"/>
                        </li>
                    </ul>
                </x-card>

                <x-card>
                    <div>
                        <h2 class="text-lg leading-6 font-medium text-gray-900">Change Password</h2>
                    </div>
                    <ul role="list" class="mt-2 divide-y divide-gray-200">
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Current Password</x-label>
                            <x-input class="min-w-max" type="password" name="current_password"/>
                        </li>
                        <li class="py-4 flex items-center justify-between">
                            <x-label>New Password</x-label>
                            <x-input class="min-w-max" type="password" name="new_password"/>
                        </li>
                        <li class="py-4 flex items-center justify-between">
                            <x-label>Confirmed Password</x-label>
                            <x-input class="min-w-max" type="password" name="new_password_confirmation"/>
                        </li>
                    </ul>
                </x-card>

                <div class="flex justify-end">
                    <x-button>Save Changes</x-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
