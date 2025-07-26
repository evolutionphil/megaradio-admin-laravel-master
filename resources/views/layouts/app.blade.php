<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @stack('external_styles')

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('styles')

        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }
        </style>
        <x-rich-text-trix-styles />

    </head>
    <body class="font-sans antialiased">
        <video class="hidden" id="player"></video>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-800">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-700 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <div class="max-w-7xl mx-auto py-2 px-4 lg:px-8">
                <x-alerts />
                <x-toast />
            </div>


            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 lg:px-8 py-4">
                {{ $slot }}
            </main>
        </div>

        @include('pages.admin.radio-stations.partials.player')

        <!-- Scripts -->

        @stack('external_scripts')

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        @livewireScripts
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/utils.js') }}"></script>

        @stack('scripts')

    </body>
</html>
