@extends('layouts.teacher')

@section('css')
    @vite(['resources/css/teacher/calendar.css', 'resources/js/teacher/calendar-colors.js', 'resources/js/teacher/calendar-course-list.js', 'resources/js/teacher/calendar-event-modal.js', 'resources/js/teacher/calendar.js', 'resources/css/modal.css', 'resources/css/teacher/course-card.css'])
@endsection

@section('left_sidebar')
    <!-- Course List Header -->
    <div class="p-4 border-b border-[#e8e3db]">
        <h3 class="font-semibold text-[#1a1714] text-sm">My Courses</h3>
        <p class="text-xs text-[#6b6560] mt-1" id="courseCount">Loading...</p>
    </div>

    <!-- Course Cards -->
    <div id="courseList" class="course-list flex-1 px-3 py-2 flex flex-col gap-1">
        <!-- Courses will be loaded here -->
    </div>

    <!-- Action Buttons -->
    <div class="p-4 border-t border-[#e8e3db]">
        <button onclick="openCreateModal()"
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#1a1714] text-white rounded-lg text-sm font-medium cursor-pointer transition-all hover:bg-[#333] hover:-translate-y-px active:translate-y-0">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            New Course
        </button>
    </div>
@endsection

@section('header')
    <div>
        <h1 class="font-serif text-2xl font-semibold text-[#1a1714]">Teacher Calendar</h1>
        <p class="text-xs text-[#6b6560] mt-1">Semester: Week 1 (2 Mar) → Week 19 (12 Jul)</p>
    </div>

    <button onclick="openCreateModal()"
        class="flex items-center gap-2 px-5 py-2.5 bg-[#1a1714] text-white rounded-lg text-sm font-medium cursor-pointer transition-all hover:bg-[#333] hover:-translate-y-px active:translate-y-0">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        New Course
    </button>
@endsection

@section('content')
    <div class="p-6">
        <div class="bg-[#fdfcf9] rounded-xl border border-[#e8e3db] shadow-sm p-6">
            <div id="calendar"></div>
            <div class="mt-4 flex gap-5 justify-center flex-wrap">
                <div class="flex items-center gap-1.5 text-xs text-[#6b6560]">
                    <div class="w-2 h-2 rounded-full bg-[#c4bfb8]"></div>
                    Click event to edit course
                </div>
                <div class="flex items-center gap-1.5 text-xs text-[#6b6560]">
                    <div class="w-2 h-2 rounded-full bg-[#c4bfb8]"></div>
                    Click a time slot to create
                </div>
            </div>
        </div>
    </div>
@endsection

@section('others')
    @include('teacher.components.calendar-event-modal')
@endsection

@section('js')
    <script>
        window.teacherCalendarData = {
            adminId: @json(auth('admin')->id()),
        };
    </script>
@endsection
