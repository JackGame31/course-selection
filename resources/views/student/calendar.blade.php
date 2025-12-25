@extends('layouts.student')

@section('css')
    @vite(['resources/css/student/calendar.css', 'resources/js/student/calendar.js'])
@endsection

@section('sidebar')
    {{-- course list --}}
    <x-select-class :courses="$courses" :is-finish="$isFinish">
    </x-select-class>

    {{-- reset course selection --}}
    @if ($isFinish)
        <form method="POST" action="{{ route('student.resetCourseSelection') }}" class="my-3">
            @method('PUT')
            @csrf
            <button type="submit" class="button button--warning button--lg w-full">
                Reset Course Selection
            </button>
        </form>
    @endif
@endsection

@section('nav')
    @include('student.components.nav-calendar')
@endsection

@section('content')
    {{-- main calendar --}}
    <div class="calendar" data-calendar>
    </div>
@endsection

@section('others')
    {{-- calendar template --}}
    @include('components.calendar-template')
    {{-- dialogs --}}
    @include('student.components.detail-course')
    @include('student.components.submit-course')
    {{-- others --}}
    @include('components.spinner')
@endsection
