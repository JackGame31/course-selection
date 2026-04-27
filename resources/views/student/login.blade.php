@extends('layouts.main')

@section('css')
    <style>
        .auth-card {
            background: #fdfcf9;
            border: 1px solid #e8e3db;
            border-radius: 20px;
            box-shadow: 0 2px 40px rgba(26, 23, 20, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        }

        .auth-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #6b6560;
            margin-bottom: 6px;
        }

        .auth-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e8e3db;
            border-radius: 10px;
            font-size: 14px;
            color: #1a1714;
            background: #f7f4ef;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
            outline: none;
            box-sizing: border-box;
        }

        .auth-input::placeholder {
            color: #b0aaa3;
        }

        .auth-input:hover {
            border-color: #c4bfb8;
            background: #f3efe8;
        }

        .auth-input:focus {
            border-color: #1a1714;
            background: #fdfcf9;
            box-shadow: 0 0 0 3px rgba(26, 23, 20, 0.07);
        }

        .auth-input.is-error {
            border-color: #d4483a;
            background: #fdf5f4;
        }

        .auth-btn-primary {
            width: 100%;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            background: #1a1714;
            color: #fff;
            border: 1px solid transparent;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.15s, transform 0.1s;
            letter-spacing: 0.01em;
        }

        .auth-btn-primary:hover {
            background: #333;
        }

        .auth-btn-primary:active {
            transform: scale(0.98);
        }

        .auth-divider {
            height: 1px;
            background: #e8e3db;
            margin: 4px -28px;
        }

        .auth-link {
            color: #1a1714;
            text-decoration: underline;
            text-underline-offset: 2px;
            font-weight: 500;
            transition: color 0.15s;
        }

        .auth-link:hover {
            color: #6b6560;
        }

        .auth-checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid #c4bfb8;
            border-radius: 4px;
            background: #f7f4ef;
            accent-color: #1a1714;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>
@endsection

@section('content')
    <div class="w-full max-w-sm mx-auto px-4">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-[#1a1714]" style="font-family: 'Lora', serif; letter-spacing: -0.01em;">
                {{ config('app.name') }}
            </h1>
            <p class="text-sm text-[#6b6560] mt-1">Sign in to continue</p>
        </div>

        <div class="auth-card px-7 py-8">

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="auth-label">Email address</label>
                    <input type="email" name="email" id="email" placeholder="name@email.com"
                        class="auth-input @error('email') is-error @enderror" value="{{ old('email') }}" required
                        autocomplete="email">
                    @error('email')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="auth-label" style="margin-bottom:0;">Password</label>
                        <a href="#"
                            class="text-xs text-[#6b6560] hover:text-[#1a1714] underline underline-offset-2 transition-colors">
                            Forgot password?
                        </a>
                    </div>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="auth-input @error('password') is-error @enderror" required autocomplete="current-password">
                    @error('password')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-start gap-2.5">
                    <input id="remember" name="remember" type="checkbox" class="auth-checkbox">
                    <label for="remember" class="text-sm text-[#6b6560] leading-snug cursor-pointer select-none">
                        Keep me signed in
                    </label>
                </div>

                <div class="auth-divider"></div>

                {{-- Submit --}}
                <button type="submit" class="auth-btn-primary">
                    Sign in
                </button>
            </form>

        </div>

        {{-- Footer links --}}
        <div class="mt-5 text-center space-y-2">
            <p class="text-sm text-[#6b6560]">
                No account?
                <a href="{{ route('register') }}" class="auth-link">Create one</a>
            </p>
            <p class="text-sm text-[#6b6560]">
                Are you a teacher?
                <a href="{{ route('teacher.login') }}" class="auth-link">Teacher sign-in</a>
            </p>
        </div>

    </div>
@endsection
