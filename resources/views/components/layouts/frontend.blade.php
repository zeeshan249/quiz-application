<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])



    {{ $head ?? '' }}

    @stack('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>

    {{ $slot??'' }}

    @livewireScripts
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @stack('scripts')
    

</body>

</html>
