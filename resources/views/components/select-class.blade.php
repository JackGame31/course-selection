@props(['courses', 'isFinish' => false])

@php
    $days = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    function minutesToTime($minutes)
    {
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }
@endphp

{{-- select class --}}
<div class="mx-auto px-4 my-3">
    <h2 class="font-bold text-gray-800 mb-6 text-center">
        @if (!$isFinish)
            Available Classes
        @else
            Course Selected
        @endif
    </h2>

    <ul id="classList" class="space-y-2 overflow-y-auto overflow-x-hidden max-h-[60vh]"
        data-isFinish="{{ $isFinish }}">
        @forelse ($courses as $course)
            <li class="class-item min-w-0 flex justify-between items-center p-3 rounded-md bg-white
                       cursor-pointer transition-colors duration-150"
                data-id="{{ $course->id }}" data-title="{{ $course->title }}" data-day="{{ $course->day }}"
                data-start="{{ $course->startTime }}" data-end="{{ $course->endTime }}" data-color="{{ $course->color }}"
                data-teacher="{{ $course->teacher->name }}" style="--accent: {{ $course->color }}">
                <div class="flex items-center gap-2 min-w-0 w-full">
                    {{-- Checkbox --}}
                    <input type="checkbox" class="course-checkbox main mr-2 flex-shrink-0" data-id="{{ $course->id }}"
                        @if ($isFinish) checked disabled @endif />

                    {{-- Title & Time --}}
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $course->title }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $days[$course->day] }}
                            {{ minutesToTime($course->startTime) }} –
                            {{ minutesToTime($course->endTime) }}
                        </p>
                    </div>

                    {{-- Details Button --}}
                    <button class="detail-btn flex-shrink-0 text-sm text-blue-500 hover:underline"
                        data-id="{{ $course->id }}">
                        Details
                    </button>
                </div>
            </li>
        @empty
            <li class="text-center text-gray-500 py-6">
                There are no courses yet.
            </li>
        @endforelse
    </ul>
</div>

{{-- submit button --}}
@if (!$isFinish)
    <button class="button button--primary button--lg mb-3 w-full" data-course-submit-button>Submit Course
        Selection</button>
@endif
