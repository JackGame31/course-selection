@php
    $title = $title ?? 'My Schedule';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} @isset($title)
            | {{ $title }}
        @endisset
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/toast.js', 'resources/css/student/toast.css'])
    @yield('css')
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">
    @include('student.components.navbar')

    <div class="pt-14 flex h-screen overflow-hidden">
        @if (View::hasSection('left_sidebar'))
            <aside class="w-[288px] flex-shrink-0 bg-white border-r border-gray-100 flex flex-col h-full gap-4">
                @yield('left_sidebar')
            </aside>
        @endif

        <main class="flex-1 flex flex-col overflow-hidden @if (!View::hasSection('left_sidebar')) bg-white @endif">
            @if (View::hasSection('header'))
                <div class="px-6 pt-5 pb-3 bg-white border-b border-gray-100 flex items-center justify-between">
                    @yield('header')
                </div>
            @endif

            <div class="flex-1 overflow-y-auto">
                @yield('content')
            </div>
        </main>

        @if (View::hasSection('right_sidebar'))
            <aside class="w-[272px] flex-shrink-0 border-l border-gray-100 bg-white flex flex-col">
                @yield('right_sidebar')
            </aside>
        @endif
    </div>

    @yield('others')

    {{-- Toast container --}}
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
    <script>
        window._sessions = {
            success: @json(session('success')),
            error: @json(session('error')),
        };
    </script>

    @yield('js')
</body>

</html>
