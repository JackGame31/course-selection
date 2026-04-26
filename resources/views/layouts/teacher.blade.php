@php
    $title = 'Teacher Course Management';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }} @isset($title)
            | {{ $title }}
        @endisset
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-id" content="{{ auth('admin')->id() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('css')
</head>

<body class="bg-[#f7f4ef] text-[#1a1714] min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6 py-7 pb-12">
        {{-- header  --}}
        <div class="flex items-center justify-between mb-7">
            @yield('header')
        </div>

        {{-- main content --}}
        @yield('content')
    </div>

    {{-- other components, such as modals --}}
    @yield('others')

    {{-- script --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
    @yield('js')
</body>

</html>
