@extends('layouts.student')

@section('css')
    @vite(['resources/css/student/calendar.css', 'resources/js/student/calendar-student-colors.js', 'resources/js/student/calendar-student-course-list.js', 'resources/js/student/calendar-student-modal.js', 'resources/js/student/calendar-student-credits.js', 'resources/js/student/calendar-student-calendar.js', 'resources/js/student/calendar.js'])
@endsection

@section('left_sidebar')
    {{-- course list --}}
    @include('student.components.course-list')
@endsection

@section('content')
    <!-- ── CENTER: Calendar ── -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <div class="px-6 pt-5 pb-3 bg-white border-b border-gray-100">
            <h1 class="font-serif text-2xl font-semibold text-gray-900">My Schedule</h1>
            <p class="text-sm text-gray-500 mt-0.5">Plan your courses and manage your weekly schedule.</p>
        </div>

        <div class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex-1">
                <div id="calendar"></div>

                <!-- Legend -->
                <div id="legend" class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-4"></div>
            </div>

            <!-- Warning banner (shown when credits insufficient) -->
            <div id="creditWarning"
                class="hidden items-center gap-4 px-5 py-3.5 bg-amber-50 border border-amber-200 rounded-xl">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"
                    class="flex-shrink-0">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800" id="warningTitle">Select more courses to meet
                        graduation requirements</p>
                    <p class="text-xs text-amber-700 mt-0.5" id="warningBody"></p>
                </div>
                <button onclick="document.getElementById('rightCredit').scrollIntoView({behavior:'smooth'})"
                    class="text-sm font-medium text-amber-800 border border-amber-300 px-4 py-1.5 rounded-lg hover:bg-amber-100 transition-colors whitespace-nowrap">
                    View Credit Summary
                </button>
            </div>
        </div>
    </main>
@endsection


@section('right_sidebar')
    <!-- ── RIGHT: Summary ── -->
    <aside class="w-[272px] flex-shrink-0 border-l border-gray-100 bg-white sidebar-scroll flex flex-col">
        {{-- Credit Summary --}}
        @include('student.components.credit-summary')

        @if (!$isFinish)
            <!-- Suggested courses -->
            @include('student.components.course-suggestion')
        @endif
    </aside>
@endsection


@section('others')
    @include('student.components.course-detail-modal')
@endsection

@section('js')
    <script>
        window.studentCalendarData = {
            studentId: @json(auth()->id()),
            isFinish: @json($isFinish ?? false),
            courses: @json($courses ?? []),
        };
    </script>
@endsection
