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

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/toast.js', 'resources/css/teacher/toast.css'])
    @yield('css')
</head>

<body class="bg-[#f7f4ef] text-[#1a1714] min-h-screen">
    <!-- Navigation Bar -->
    @include('teacher.components.navbar')

    <!-- Main Layout -->
    <div class="pt-14 flex h-screen overflow-hidden">
        {{-- Optional left sidebar --}}
        @if (View::hasSection('left_sidebar'))
            <aside class="w-[288px] flex-shrink-0 bg-[#fdfcf9] border-r border-[#e8e3db] flex flex-col h-full">
                @yield('left_sidebar')
            </aside>
        @endif

        {{-- Main content area --}}
        <main class="flex-1 flex flex-col overflow-hidden @if (!View::hasSection('left_sidebar')) bg-[#fdfcf9] @endif">
            {{-- Page header with title and actions --}}
            @if (View::hasSection('header'))
                <div class="px-6 pt-5 pb-3 bg-[#fdfcf9] border-b border-[#e8e3db] flex items-center justify-between">
                    @yield('header')
                </div>
            @endif

            {{-- Main content --}}
            <div class="flex-1 overflow-y-auto">
                @yield('content')
            </div>
        </main>

        {{-- Optional right sidebar --}}
        @if (View::hasSection('right_sidebar'))
            <aside class="w-[272px] flex-shrink-0 border-l border-[#e8e3db] bg-[#fdfcf9] flex flex-col">
                @yield('right_sidebar')
            </aside>
        @endif
    </div>

    {{-- Other components, such as modals --}}
    @yield('others')

    {{-- Toast container --}}
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
    <script>
        _sessions = {
            success: @json(session('success')),
            error: @json(session('error')),
        };
    </script>

    @yield('js')
</body>

</html>
