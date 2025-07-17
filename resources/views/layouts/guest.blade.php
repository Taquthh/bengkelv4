<!DOCTYPE html>
<html class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
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
    <body class="min-h-screen font-sans antialiased bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex flex-col">
        <div class="flex-grow flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
            <!-- Logo -->
            <div class="mb-6">
                <a href="/" aria-label="Homepage">
                    <x-application-logo class="w-20 h-20 text-gray-500 dark:text-gray-400" />
                </a>
            </div>

            <!-- Page Content -->
            <div class="w-full max-w-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
