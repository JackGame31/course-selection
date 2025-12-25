<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        {{ config('app.name') }} @isset($title)
            | {{ $title }}
        @endisset
    </title>
    @yield('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-600">
    @yield('content')

    @yield('others')

    @yield('js')

    {{-- get session message --}}
    <script>
        const sessionMessages = {
            success: @json(session('success')),
            error: @json(session('error')),
        };
    </script>
</body>

</html>
