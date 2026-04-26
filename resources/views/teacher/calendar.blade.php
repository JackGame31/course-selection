@extends('layouts.teacher')

@section('css')
    @vite(['resources/css/teacher/calendar.css', 'resources/js/teacher/calendar.js', 'resources/css/modal.css'])
@endsection

@section('header')
    <div>
        <div class="font-serif text-[28px] font-semibold tracking-tight">
            Teacher Dashboard <span class="text-[#c0392b]">Calendar</span>
        </div>
        <div class="text-xs text-[#6b6560] mt-1">Semester: Week 1 (2 Mar) &rarr; Week 19 (12 Jul)</div>
    </div>

    <div class="flex items-center gap-3">
        <button onclick="openCreateModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-[#1a1714] text-white rounded-lg text-sm font-medium cursor-pointer transition-all hover:bg-[#333] hover:-translate-y-px active:translate-y-0">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
            New Course
        </button>

        {{-- TODO: create sidebar and move the logout button there --}}
        <form method="POST" action="{{ route('teacher.logout') }}" style="margin: 0;">
            @method('DELETE')
            @csrf
            <button type="submit"
                class="flex items-center px-5 py-2.5 bg-[#fdfcf9] text-[#6b6560] border border-[#e8e3db] rounded-lg text-sm font-medium cursor-pointer transition-all hover:bg-[#fef1f0] hover:text-[#c0392b] hover:border-[#f5c6c2] hover:-translate-y-px">
                Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="bg-[#fdfcf9] rounded-2xl border border-[#e8e3db] shadow-sm p-6">
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
@endsection

@section('others')
    @include('teacher.components.calendar-event-modal')
@endsection
