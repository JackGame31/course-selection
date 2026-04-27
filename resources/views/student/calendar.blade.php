@extends('layouts.student')

@section('css')
    @vite(['resources/css/student/calendar.css', 'resources/js/student/calendar.js'])
@endsection

@section('left_sidebar')
    <!-- ── LEFT: Course list ── -->
    <aside class="w-[288px] flex-shrink-0 bg-white border-r border-gray-100 flex flex-col h-full">
        @if ($isFinish)
            <!-- Finalized message -->
            <div class="p-4 border-b border-gray-100 bg-emerald-50">
                <div class="flex items-center gap-2 mb-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <h2 class="font-semibold text-gray-900 text-[15px]">Schedule Finalized</h2>
                </div>
                <p class="text-xs text-emerald-700">Your course selection has been submitted. Click reset if you
                    want to make changes.</p>
            </div>
        @endif
        <div class="p-4 border-b border-gray-100" @if ($isFinish) style="display: none;" @endif>
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-gray-900 text-[15px]">Courses This Week</h2>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Info">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                </button>
            </div>

            <!-- Search -->
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input id="courseSearch" type="text" placeholder="Search courses..."
                        class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors" />
                </div>
                <button class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                        <line x1="11" y1="18" x2="13" y2="18" />
                    </svg>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex mt-3 border-b border-gray-100">
                <button id="tabAll" onclick="switchTab('all')"
                    class="tab-active flex-1 text-sm font-medium py-2 transition-colors">All Courses</button>
                <button id="tabSelected" onclick="switchTab('selected')"
                    class="tab-inactive flex-1 text-sm font-medium py-2 transition-colors">
                    Selected (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <!-- Course cards -->
        <div id="courseList" class="course-list flex-1 px-3 py-2 flex flex-col gap-1"
            @if ($isFinish) style="display: none;" @endif></div>

        <!-- Action buttons -->
        <div class="p-4 border-t border-gray-100 flex flex-col gap-2">
            @if (!$isFinish)
                <!-- Submit button -->
                <form id="submitForm" method="POST" action="{{ route('student.submitCourseSelection') }}">
                    @csrf
                    <div id="selectedCoursesInput"></div>
                    <button type="button" onclick="handleSubmit()"
                        class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg transition-colors border
                            bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed"
                        id="submitBtn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Finalize Schedule
                    </button>
                </form>

                <!-- View All Courses button -->
                <button
                    class="w-full flex items-center justify-center gap-2 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    View All Courses
                </button>
            @else
                <!-- Reset button -->
                <form method="POST" action="{{ route('student.resetCourseSelection') }}">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors"
                        onclick="return confirm('Are you sure you want to reset your course selection?')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                            <path d="M21 3v5h-5" />
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                            <path d="M3 21v-5h5" />
                        </svg>
                        Reset Course Selection
                    </button>
                </form>
            @endif
        </div>
    </aside>
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
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706"
                    stroke-width="2" class="flex-shrink-0">
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
        <div id="rightCredit" class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 text-[15px]">Credit Summary</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                </button>
            </div>

            <!-- Total credits -->
            <div class="mb-4">
                <p class="text-xs text-gray-500 mb-1">Total Selected</p>
                <div class="flex items-baseline gap-1.5">
                    <span id="totalCreditsNum" class="text-4xl font-bold text-red-500">0</span>
                    <span class="text-sm text-gray-500 font-medium">/ 15 Credits</span>
                </div>
                <div class="progress-bar mt-2">
                    <div id="totalProgressBar" class="progress-fill bg-red-500" style="width:0%"></div>
                </div>
                <p id="creditGapMsg" class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                    <span id="creditGapText">Select 15 more credits</span>
                </p>
            </div>

            <!-- Category breakdown -->
            <div class="flex flex-col gap-3" id="categoryBreakdown"></div>

            <button
                class="mt-4 w-full py-2 text-sm font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                View Details
            </button>
        </div>

        <!-- Suggested courses -->
        @if (!$isFinish)
            <div class="p-5 flex-1">
                <h3 class="font-semibold text-gray-900 text-[15px] mb-0.5">Suggested Courses for You</h3>
                <p class="text-xs text-gray-400 mb-4">Based on your program and interests</p>
                <div id="suggestedList" class="flex flex-col gap-3"></div>
                <button
                    class="mt-4 w-full py-2 text-sm text-indigo-600 font-medium flex items-center justify-center gap-1.5 hover:underline transition-colors">
                    View More Suggestions
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>
            </div>
        @endif
    </aside>
@endsection

@section('others')
    <div id="courseDetailModal" onclick="if (event.target === this) closeCourseDetailModal()"
        class="modal-overlay fixed inset-0 bg-black/45 backdrop-blur-sm z-[9999] items-center justify-center px-4 py-6">
        <div
            class="modal bg-[#fdfcf9] rounded-2xl w-[500px] max-w-[calc(100vw-40px)] max-h-[92vh] overflow-y-auto shadow-2xl border border-[#e8e3db]">

            <div
                class="px-6 pt-[22px] pb-[18px] border-b border-[#e8e3db] flex items-center justify-between sticky top-0 bg-[#fdfcf9] z-10">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-indigo-600 font-semibold">Course Detail</p>
                    <h2 id="courseDetailTitle" class="mt-3 text-2xl font-semibold text-gray-900"></h2>
                </div>
                <button type="button" onclick="closeCourseDetailModal()"
                    class="w-[30px] h-[30px] flex items-center justify-center bg-transparent border-none cursor-pointer text-[#6b6560] rounded-md text-xl leading-none hover:bg-[#f7f4ef] hover:text-[#1a1714] transition-colors">
                    &#x2715;
                </button>
            </div>

            <div class="px-6 py-5 flex flex-col gap-4">
                <div class="flex flex-wrap gap-2 text-sm">
                    <span id="courseDetailCode" class="rounded-full bg-gray-100 px-3 py-1 text-gray-700"></span>
                    <span id="courseDetailCategory" class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700"></span>
                    <span id="courseDetailCredits" class="rounded-full bg-indigo-50 px-3 py-1 text-indigo-700"></span>
                    <span id="courseDetailTeacher" class="rounded-full bg-slate-100 px-3 py-1 text-slate-700"></span>
                </div>
                <p id="courseDetailDescription" class="text-sm leading-6 text-gray-600"></p>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Schedule</h3>
                    <div id="courseDetailSchedule" class="space-y-3"></div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#e8e3db] flex gap-2.5 justify-end sticky bottom-0 bg-[#fdfcf9]">
                <button type="button" onclick="closeCourseDetailModal()"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer bg-[#f7f4ef] text-[#6b6560] border border-[#e8e3db] transition-colors hover:bg-[#ece7df] active:scale-[0.97]">
                    Close
                </button>
                <button id="courseDetailActionBtn" type="button"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer bg-[#1a1714] text-white border border-transparent transition-colors hover:bg-[#333] active:scale-[0.97]">
                    Add to schedule
                </button>
            </div>
        </div>
    </div>
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
