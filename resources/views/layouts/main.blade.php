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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @yield('css')
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/toast.js'])
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        /* Toast */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            pointer-events: all;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid #e8e3db;
            background: #fdfcf9;
            box-shadow: 0 4px 24px rgba(26, 23, 20, 0.10);
            font-size: 14px;
            color: #1a1714;
            min-width: 280px;
            max-width: 360px;
            animation: toast-in 0.3s ease;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .toast.success .toast-icon {
            color: #2d7a4f;
        }

        .toast.error .toast-icon {
            color: #c0392b;
        }

        .toast-icon {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .toast-body {
            flex: 1;
            line-height: 1.5;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b6560;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            flex-shrink: 0;
            transition: color 0.15s;
        }

        .toast-close:hover {
            color: #1a1714;
        }

        .toast.hiding {
            opacity: 0;
            transform: translateX(16px);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateX(16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center"
    style="background: #f4f0e8; background-image: radial-gradient(ellipse at 20% 20%, #ede7d9 0%, transparent 60%), radial-gradient(ellipse at 80% 80%, #e8dfd0 0%, transparent 60%);">

    {{-- Toast container --}}
    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>

    @yield('content')
    @yield('others')
    @yield('js')

    <script>
        const _sessions = {
            success: @json(session('success')),
            error: @json(session('error')),
        };
    </script>
</body>

</html>
