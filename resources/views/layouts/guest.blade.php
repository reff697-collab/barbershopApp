<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-surface">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-coral-400 to-coral-600 flex items-center justify-center px-3 py-3">
                    <span class="text-white text-lg font-semibold">B</span>
                </div>
                <span class="text-lg font-semibold text-gray-800">Barbershop</span>
            </div>

            <div class="w-full sm:max-w-md px-6 py-10 bg-white rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>