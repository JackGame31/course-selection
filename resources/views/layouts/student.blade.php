@php
    $title = 'Student Course Selection';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} @isset($title)
            | {{ $title }}</title>
    @endisset

    @yield('css')
    @vite(['resources/css/student/index.css', 'resources/js/student/index.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="app">
        {{-- sidebar --}}
        <x-sidebar :title="$title">
            @yield('sidebar')

            {{-- logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="button button--danger button--lg w-full">
                    Logout
                </button>
            </form>
        </x-sidebar>

        <main class="main">
            {{-- navigation --}}
            <x-nav>
                {{-- sidebar hamburger --}}
                @include('components.hamburger')
                @yield('nav')
            </x-nav>

            @yield('content')
        </main>
    </div>

    @yield('others')

    {{-- mobile sidebar --}}
    <x-mobile-sidebar :title="$title">
        @yield('sidebar')
    </x-mobile-sidebar>

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
