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

        .pw-hint {
            font-size: 11px;
            color: #6b6560;
            margin-top: 5px;
            line-height: 1.4;
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
            <p class="text-sm text-[#6b6560] mt-1">Create your student account</p>
        </div>

        <div class="auth-card px-7 py-8">

            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="auth-label">Full name</label>
                    <input type="text" name="name" id="name" placeholder="John Doe"
                        class="auth-input @error('name') is-error @enderror" value="{{ old('name') }}" required
                        autocomplete="name">
                    @error('name')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="password" class="auth-label">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••"
                        class="auth-input @error('password') is-error @enderror" required autocomplete="new-password">
                    @error('password')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @else
                        <p class="pw-hint">Use at least 8 characters</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="confirm-password" class="auth-label">Confirm password</label>
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••"
                        class="auth-input @error('confirm-password') is-error @enderror" required
                        autocomplete="new-password">
                    @error('confirm-password')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terms --}}
                <div>
                    <div class="flex items-start gap-2.5">
                        <input id="terms" name="terms" type="checkbox" class="auth-checkbox"
                            @if (old('terms')) checked @endif required>
                        <label for="terms" class="text-sm text-[#6b6560] leading-snug cursor-pointer select-none">
                            I agree to the
                            <a href="#" class="auth-link">Terms and Conditions</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-xs text-[#c0392b] mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-divider"></div>

                {{-- Submit --}}
                <button type="submit" class="auth-btn-primary">
                    Create account
                </button>
            </form>

        </div>

        {{-- Footer links --}}
        <div class="mt-5 text-center space-y-2">
            <p class="text-sm text-[#6b6560]">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link">Sign in</a>
            </p>
            <p class="text-sm text-[#6b6560]">
                Are you a teacher?
                <a href="{{ route('teacher.register') }}" class="auth-link">Teacher registration</a>
            </p>
        </div>

    </div>
@endsection
