<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/login.css', 'resources/js/login.js'])
    {{ $head ?? '' }}
    @stack('styles')
</head>
<body>
    {{ $slot }}
    @stack('scripts')
</body>
</html>
