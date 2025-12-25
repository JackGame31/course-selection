@extends('layouts.teacher')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/teacher/calendar.css', 'resources/js/teacher/calendar.js'])
@endsection

@section('sidebar')
    <button class="button button--primary button--lg w-full my-3" data-event-create-button>Create event</button>
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
    @include('teacher.components.event-form')
    @include('teacher.components.event-detail')
    @include('teacher.components.event-delete')

    {{-- others --}}
    @include('teacher.components.fab')
    @include('components.spinner')
@endsection

@section('js')
    <script>
        courses = @json($courses);
        admin_id =
            {{ auth()->guard('admin')->user()->id }};
    </script>
@endsection
