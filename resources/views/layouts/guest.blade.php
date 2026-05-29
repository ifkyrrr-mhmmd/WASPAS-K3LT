<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Autentikasi - WASPAS - K3LT</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-slate-50 min-h-screen text-slate-800 antialiased selection:bg-blue-500 selection:text-white flex flex-col justify-center items-center py-12 px-4 sm:px-6">
        <div class="mb-8">
            <a href="/" class="flex items-center justify-center">
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@800&display=swap" rel="stylesheet">
                <span style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-4xl font-extrabold tracking-tight text-slate-800">
                    WASPAS - <span class="text-emerald-600">K3LT</span>
                </span>
            </a>
        </div>

        <div class="w-full sm:max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100">
            {{ $slot }}
        </div>
    </body>
</html>
