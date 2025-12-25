@extends('layouts.main')

@section('content')
    <div
        class="relative w-full max-w-md mx-4
                backdrop-blur-xl bg-white/90
                rounded-2xl shadow-2xl p-8 text-center
                animate-fade-in">

        {{-- logo / icon --}}
        <div
            class="mx-auto mb-4 w-14 h-14 rounded-full
                    bg-gradient-to-tr from-blue-500 to-indigo-600
                    flex items-center justify-center text-white shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path d="M4 6h16M4 10h16M4 14h10" />
            </svg>
        </div>

        {{-- title --}}
        <h1 class="text-2xl font-extrabold text-gray-800 mb-1">
            {{ config('app.name') }}
        </h1>

        <p class="text-gray-500 mb-8">
            Welcome!
        </p>

        {{-- buttons --}}
        {{-- buttons --}}
        <div class="space-y-4">

            {{-- ================= TEACHER (ADMIN) ================= --}}
            @auth('admin')
                {{-- go to teacher dashboard --}}
                <a href="{{ route('teacher.calendar') }}"
                    class="group flex items-center justify-between gap-3
                   w-full px-5 py-4 rounded-xl
                   bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white font-semibold
                   shadow-md hover:shadow-xl
                   transition-all duration-200
                   hover:-translate-y-0.5">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            🎓
                        </div>
                        Go to Teacher Dashboard
                    </div>

                    <span>→</span>
                </a>

                {{-- teacher logout --}}
                <form method="POST" action="{{ route('teacher.logout') }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full px-5 py-4 rounded-xl
                       bg-red-600 text-white font-semibold
                       hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            @endauth


            {{-- ================= STUDENT ================= --}}
            @auth
                {{-- go to student dashboard --}}
                <a href="{{ route('student.calendar') }}"
                    class="group flex items-center justify-between gap-3
                   w-full px-5 py-4 rounded-xl
                   bg-gradient-to-r from-gray-800 to-gray-700
                   text-white font-semibold
                   shadow-md hover:shadow-xl
                   transition-all duration-200
                   hover:-translate-y-0.5">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            👩‍🎓
                        </div>
                        Go to Student Dashboard
                    </div>

                    <span>→</span>
                </a>

                {{-- student logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full px-5 py-4 rounded-xl
                       bg-red-600 text-white font-semibold
                       hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            @endauth


            {{-- ================= GUEST ================= --}}
            @if (!Auth::guard('admin')->check() && !Auth::guard()->check())
                {{-- teacher login --}}
                <a href="{{ route('teacher.login') }}"
                    class="group flex items-center justify-between gap-3
                   w-full px-5 py-4 rounded-xl
                   bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white font-semibold
                   shadow-md hover:shadow-xl
                   transition-all duration-200
                   hover:-translate-y-0.5">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            🎓
                        </div>
                        Login as Teacher
                    </div>

                    <span>→</span>
                </a>

                {{-- student login --}}
                <a href="{{ route('login') }}"
                    class="group flex items-center justify-between gap-3
                   w-full px-5 py-4 rounded-xl
                   bg-gradient-to-r from-gray-800 to-gray-700
                   text-white font-semibold
                   shadow-md hover:shadow-xl
                   transition-all duration-200
                   hover:-translate-y-0.5">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            👩‍🎓
                        </div>
                        Login as Student
                    </div>

                    <span>→</span>
                </a>
            @endif

        </div>

        {{-- footer --}}
        <p class="mt-8 text-xs text-gray-400">
            © {{ date('Y') }} {{ config('app.name') }} · All rights reserved
        </p>
    </div>
@endsection
