<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Feedback') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1">
        <x-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-label class="font-bold" for="name" :value="__('Email')"/>
                    <h2 class="break-all">{{ $feedback->email ?: 'N/A' }}</h2>
                </div>
                <div>
                    <x-label class="font-bold" for="message" :value="__('Message')"/>
                    <p class="break-all">{{ $feedback->message }}</p>
                </div>

                @if($feedback->radioStation)
                    <div>
                        <x-label class="font-bold" for="message" :value="__('Station')"/>
                        <p class="break-all">
                            <a class="text-blue-400" href="{{ route('admin.radio-stations.show', $feedback->radioStation) }}">Show Station</a>
                        </p>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</x-app-layout>
