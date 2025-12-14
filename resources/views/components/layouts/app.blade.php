<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'NANDA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="font-bold text-xl text-indigo-600">NANDA</div>
            <div class="flex gap-4 text-sm">
                <a href="{{ route('lang.switch', 'es') }}"
                    class="{{ app()->getLocale() === 'es' ? 'font-bold text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                    Español
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="{{ app()->getLocale() === 'en' ? 'font-bold text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                    English
                </a>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>

</html>