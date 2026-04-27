@extends('layouts.main')

@section('css')
    <style>
        .welcome-card {
            background: #fdfcf9;
            border: 1px solid #e8e3db;
            border-radius: 24px;
            box-shadow: 0 2px 40px rgba(26, 23, 20, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        }

        /* Portal buttons */
        .portal-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #e8e3db;
            background: #f7f4ef;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, border-color 0.15s, transform 0.1s, box-shadow 0.15s;
            font-family: 'DM Sans', sans-serif;
        }

        .portal-btn:hover {
            background: #f0ece4;
            border-color: #c4bfb8;
            box-shadow: 0 2px 12px rgba(26, 23, 20, 0.07);
        }

        .portal-btn:active {
            transform: scale(0.985);
        }

        .portal-btn-dark {
            background: #1a1714;
            border-color: transparent;
            color: #fff;
        }

        .portal-btn-dark:hover {
            background: #2c2825;
            border-color: transparent;
            box-shadow: 0 4px 16px rgba(26, 23, 20, 0.18);
        }

        .portal-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .portal-icon-light {
            background: #ece7df;
        }

        .portal-icon-dark {
            background: rgba(255, 255, 255, 0.12);
        }

        .portal-label {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .portal-label-muted {
            color: #1a1714;
        }

        .portal-label-bright {
            color: #fff;
        }

        .portal-arrow {
            font-size: 15px;
            transition: transform 0.15s;
            flex-shrink: 0;
        }

        .portal-btn:hover .portal-arrow {
            transform: translateX(3px);
        }

        /* Logout button */
        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid #f0ceca;
            background: #fef5f4;
            color: #c0392b;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.15s, border-color 0.15s, transform 0.1s;
        }

        .logout-btn:hover {
            background: #fde8e5;
            border-color: #e8b4af;
        }

        .logout-btn:active {
            transform: scale(0.98);
        }

        .divider {
            height: 1px;
            background: #e8e3db;
            margin: 2px 0;
        }
    </style>
@endsection

@section('content')
    <div class="w-full max-w-xs mx-auto px-4">

        <div class="welcome-card px-7 py-8 text-center">

            {{-- App icon --}}
            <div class="mx-auto mb-5 w-12 h-12 rounded-2xl bg-[#1a1714] flex items-center justify-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#f7f4ef]" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 10h16M4 14h10" stroke-linecap="round" />
                </svg>
            </div>

            {{-- Heading --}}
            <h1 class="text-xl font-semibold text-[#1a1714] mb-1"
                style="font-family: 'Lora', serif; letter-spacing: -0.01em;">
                {{ config('app.name') }}
            </h1>
            <p class="text-sm text-[#6b6560] mb-7">Welcome! Choose your portal to continue.</p>

            <div class="space-y-3 text-left">

                {{-- ═══════════════ TEACHER (ADMIN) ═══════════════ --}}
                @auth('admin')
                    <a href="{{ route('teacher.calendar') }}" class="portal-btn portal-btn-dark">
                        <div class="flex items-center gap-3">
                            <span class="portal-icon portal-icon-dark">🎓</span>
                            <span class="portal-label portal-label-bright">Teacher Dashboard</span>
                        </div>
                        <span class="portal-arrow" style="color:#fff;">→</span>
                    </a>

                    <div class="divider"></div>

                    <form method="POST" action="{{ route('teacher.logout') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="logout-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                            </svg>
                            Sign out of teacher account
                        </button>
                    </form>
                @endauth


                {{-- ═══════════════ STUDENT ═══════════════ --}}
                @auth

                    <a href="{{ route('student.calendar') }}" class="portal-btn portal-btn-dark">
                        <div class="flex items-center gap-3">
                            <span class="portal-icon portal-icon-dark">👩‍🎓</span>
                            <span class="portal-label portal-label-bright">Student Dashboard</span>
                        </div>
                        <span class="portal-arrow" style="color:#fff;">→</span>
                    </a>

                    <div class="divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="logout-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                            </svg>
                            Sign out of student account
                        </button>
                    </form>

                @endauth


                {{-- ═══════════════ GUEST ═══════════════ --}}
                @if (!Auth::guard('admin')->check() && !Auth::guard()->check())
                    <a href="{{ route('teacher.login') }}" class="portal-btn portal-btn-dark">
                        <div class="flex items-center gap-3">
                            <span class="portal-icon portal-icon-dark">🎓</span>
                            <span class="portal-label portal-label-bright">Sign in as Teacher</span>
                        </div>
                        <span class="portal-arrow" style="color:#fff;">→</span>
                    </a>

                    <a href="{{ route('login') }}" class="portal-btn">
                        <div class="flex items-center gap-3">
                            <span class="portal-icon portal-icon-light">👩‍🎓</span>
                            <span class="portal-label portal-label-muted">Sign in as Student</span>
                        </div>
                        <span class="portal-arrow" style="color:#6b6560;">→</span>
                    </a>
                @endif

            </div>

            {{-- Footer --}}
            <p class="mt-7 text-xs text-[#b0aaa3]">
                © {{ date('Y') }} {{ config('app.name') }} · All rights reserved
            </p>
        </div>

    </div>
@endsection
