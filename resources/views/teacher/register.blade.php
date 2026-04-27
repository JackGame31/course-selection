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

        .teacher-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #f0ece4;
            border: 1px solid #e8e3db;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 500;
            color: #6b6560;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .teacher-badge svg {
            width: 12px;
            height: 12px;
        }

        .role-notice {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 14px;
            background: #f7f4ef;
            border: 1px solid #e8e3db;
            border-radius: 10px;
            font-size: 13px;
            color: #6b6560;
            line-height: 1.5;
        }

        .role-notice svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            margin-top: 1px;
            color: #b0aaa3;
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
            <p class="text-sm text-[#6b6560] mt-1">Create your teacher account</p>
        </div>

        <div class="auth-card px-7 py-8">

            {{-- Teacher badge --}}
            <div class="flex justify-center">
                <span class="teacher-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                    Teacher registration
                </span>
            </div>

            <form action="{{ route('teacher.register.store') }}" method="POST" class="space-y-5">
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
                    <label for="email" class="auth-label">Teacher email</label>
                    <input type="email" name="email" id="email" placeholder="teacher@school.com"
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

                {{-- Role notice --}}
                <div class="role-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    This account will be granted teacher privileges upon approval.
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
                    Register as teacher
                </button>
            </form>

        </div>

        {{-- Footer links --}}
        <div class="mt-5 text-center space-y-2">
            <p class="text-sm text-[#6b6560]">
                Already have a teacher account?
                <a href="{{ route('teacher.login') }}" class="auth-link">Sign in</a>
            </p>
            <p class="text-sm text-[#6b6560]">
                Are you a student?
                <a href="{{ route('register') }}" class="auth-link">Student registration</a>
            </p>
        </div>

    </div>
@endsection
