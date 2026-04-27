<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Schedule – EduPlan</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
    @vite(['resources/css/app.css', 'resources/css/modal.css', 'resources/js/app.js'])


    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Lora', serif;
        }

        /* ── FullCalendar overrides ── */
        .fc {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
        }

        .fc .fc-toolbar-title {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .fc .fc-button {
            background: transparent !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            border-radius: 6px !important;
            padding: 4px 10px !important;
            box-shadow: none !important;
            transition: background 0.12s !important;
        }

        .fc .fc-button:hover {
            background: #f3f4f6 !important;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: #1e293b !important;
            border-color: #1e293b !important;
            color: #fff !important;
        }

        .fc .fc-button:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #f3f4f6;
        }

        .fc .fc-col-header-cell-cushion {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 4px;
        }

        .fc .fc-timegrid-slot-label-cushion {
            color: #9ca3af;
            font-size: 11px;
        }

        .fc .fc-day-today {
            background: #eff6ff !important;
        }

        .fc .fc-day-today .fc-col-header-cell-cushion {
            color: #2563eb;
        }

        .fc .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 3px 7px !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }

        .fc .fc-event-title {
            font-weight: 600;
            font-size: 11px;
        }

        .fc .fc-event-time {
            font-size: 10px;
            opacity: 0.85;
        }

        .fc .fc-scrollgrid {
            border-radius: 8px;
            border: 1px solid #f3f4f6 !important;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 12px;
        }

        /* Scrollable course list */
        .course-list {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb transparent;
        }

        .course-list::-webkit-scrollbar {
            width: 4px;
        }

        .course-list::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        /* Tab underline */
        .tab-active {
            border-bottom: 2px solid #2563eb;
            color: #2563eb;
        }

        .tab-inactive {
            border-bottom: 2px solid transparent;
            color: #6b7280;
        }

        /* Course card selected state */
        .course-card {
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }

        .course-card:hover {
            background: #f9fafb;
        }

        .course-card.selected {
            border-left-color: var(--course-color);
            background: #f9fafb;
        }

        /* Legend dot */
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Progress bar */
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        /* Smooth check animation */
        @keyframes popIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .check-pop {
            animation: popIn 0.25s ease forwards;
        }

        /* Sidebar scroll */
        .sidebar-scroll {
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">

    <!-- ── Top nav ── -->
    <nav
        class="fixed top-0 left-0 right-0 h-14 bg-white border-b border-gray-100 flex items-center px-6 gap-4 z-50 shadow-sm">
        <div class="flex items-center gap-2.5 mr-8">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
            </div>
            <span class="font-serif font-semibold text-gray-900 text-[17px]">EduPlan</span>
        </div>

        <div class="flex items-center gap-1 text-sm">
            <a href="#"
                class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Dashboard</a>
            <a href="#"
                class="px-3 py-1.5 text-indigo-600 bg-indigo-50 rounded-md font-medium transition-colors">My
                Schedule</a>
            <a href="#"
                class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Courses</a>
            <a href="#"
                class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">My
                Enrollments</a>
            <a href="#"
                class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Planner</a>
        </div>

        <div class="ml-auto flex items-center gap-3">
            <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 01-3.46 0" />
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="mr-2">
                @method('DELETE')
                @csrf
                <button type="submit"
                    class="text-sm text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Logout
                </button>
            </form>
            <div class="flex items-center gap-2 cursor-pointer">
                <div
                    class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    S</div>
                <span class="text-sm font-medium text-gray-700">Student</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" class="text-gray-400">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>
        </div>
    </nav>

    <!-- ── Main layout ── -->
    <div class="pt-14 flex h-screen overflow-hidden">

        <!-- ── LEFT: Course list ── -->
        <aside class="w-[288px] flex-shrink-0 bg-white border-r border-gray-100 flex flex-col h-full">
            @if ($isFinish)
                <!-- Finalized message -->
                <div class="p-4 border-b border-gray-100 bg-emerald-50">
                    <div class="flex items-center gap-2 mb-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669"
                            stroke-width="2">
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
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" width="14"
                            height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input id="courseSearch" type="text" placeholder="Search courses..."
                            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:border-indigo-400 focus:bg-white transition-colors" />
                    </div>
                    <button
                        class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
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
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
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
    </div>

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
                    <span id="courseDetailCategory"
                        class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700"></span>
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

    <script>
        // ─────────────────────────────────────────────────────
        // STATIC DATA (same schema as admin side)
        // ─────────────────────────────────────────────────────
        const SEMESTER_START = new Date('2026-03-02T00:00:00');
        const SEMESTER_END = new Date('2026-07-12T23:59:59');

        const SESSIONS = {
            1: {
                start: '08:00',
                end: '08:50'
            },
            2: {
                start: '08:55',
                end: '09:45'
            },
            3: {
                start: '10:15',
                end: '11:05'
            },
            4: {
                start: '11:10',
                end: '12:00'
            },
            5: {
                start: '14:00',
                end: '14:50'
            },
            6: {
                start: '14:55',
                end: '15:45'
            },
            7: {
                start: '16:15',
                end: '17:05'
            },
            8: {
                start: '17:10',
                end: '18:00'
            },
            9: {
                start: '18:45',
                end: '19:35'
            },
            10: {
                start: '19:40',
                end: '20:30'
            },
            11: {
                start: '20:30',
                end: '21:20'
            },
        };

        // ─────────────────────────────────────────────────────
        // COLOR — same seeded PRNG as admin side (no color in DB)
        // For student view, seed only on course.id (no admin_id dependency)
        // ─────────────────────────────────────────────────────
        const PALETTE = ['#2563eb', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0891b2', '#65a30d', '#9333ea', '#c2410c',
            '#0284c7'
        ];

        function seededColor(courseId) {
            // MurmurHash3 finalizer — excellent avalanche for small integers
            let h = courseId;
            h = Math.imul(h ^ (h >>> 16), 0x85ebca6b) >>> 0;
            h = Math.imul(h ^ (h >>> 13), 0xc2b2ae35) >>> 0;
            h = (h ^ (h >>> 16)) >>> 0;
            return PALETTE[h % PALETTE.length];
        }

        // ─────────────────────────────────────────────────────
        // STATE
        // ─────────────────────────────────────────────────────
        const STUDENT_ID = @json(auth()->id());
        const IS_FINISH = @json($isFinish ?? false);
        const csrfToken = () => document.querySelector('meta[name="csrf-token"]').content;

        let allCourses = []; // All courses from API
        let selectedIds = new Set(); // Selected course IDs
        let currentTab = 'all';
        let searchQuery = '';

        // Credit config (adjust per university rules)
        const CREDIT_GOAL = 15;
        const CATEGORIES = {
            'Major': {
                goal: 6,
                color: '#2563eb'
            },
            'General Ed.': {
                goal: 6,
                color: '#059669'
            },
            'Internship': {
                goal: 3,
                color: '#d97706'
            },
        };

        // ─────────────────────────────────────────────────────
        // DATE HELPERS
        // ─────────────────────────────────────────────────────
        function weekToMonday(week) {
            const d = new Date(SEMESTER_START);
            d.setDate(d.getDate() + (week - 1) * 7);
            return d;
        }

        function scheduleDate(week, dayOfWeek) {
            const d = weekToMonday(week);
            d.setDate(d.getDate() + (dayOfWeek - 1));
            return d;
        }

        function isoDate(date) {
            return date.toISOString().slice(0, 10);
        }

        function semesterWeekForDate(date) {
            const n = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            if (n < SEMESTER_START || n > SEMESTER_END) return null;
            return Math.floor((n - SEMESTER_START) / (1000 * 60 * 60 * 24 * 7)) + 1;
        }

        // ─────────────────────────────────────────────────────
        // COURSE → FC EVENTS
        // ─────────────────────────────────────────────────────
        function courseToFcEvents(course) {
            const color = seededColor(course.id);
            const events = [];
            (course.schedules || []).forEach(sch => {
                const ss = SESSIONS[sch.start_session_id];
                const es = SESSIONS[sch.end_session_id];
                if (!ss || !es) return;
                const [sh, sm] = ss.start.split(':').map(Number);
                const [eh, em] = es.end.split(':').map(Number);
                for (let w = sch.start_week; w <= sch.end_week; w++) {
                    const date = scheduleDate(w, sch.day_of_week);
                    const start = new Date(date);
                    start.setHours(sh, sm, 0, 0);
                    const end = new Date(date);
                    end.setHours(eh, em, 0, 0);
                    events.push({
                        id: `c${course.id}-s${sch.id}-w${w}`,
                        title: course.title,
                        start,
                        end,
                        backgroundColor: color,
                        borderColor: color,
                        textColor: '#fff',
                        extendedProps: {
                            courseId: course.id,
                            credits: course.credits || 0
                        },
                    });
                }
            });
            return events;
        }

        // ─────────────────────────────────────────────────────
        // API
        // ─────────────────────────────────────────────────────
        async function apiFetch(path, options = {}) {
            const method = (options.method || 'GET').toUpperCase();
            const headers = {
                'Accept': 'application/json'
            };
            if (method !== 'GET') {
                headers['Content-Type'] = 'application/json';
                headers['X-CSRF-TOKEN'] = csrfToken();
            }
            const res = await fetch(`/api${path}`, {
                ...options,
                headers
            });
            if (!res.ok) {
                const e = await res.json().catch(() => ({}));
                throw new Error(e.message || `HTTP ${res.status}`);
            }
            return res.status === 204 ? null : res.json();
        }

        // ─────────────────────────────────────────────────────
        // LOAD COURSES
        // ─────────────────────────────────────────────────────
        async function loadCourses() {
            try {
                // NOTE: swap with your actual student courses endpoint
                // For a student, you likely want ALL courses, not just one admin's
                const courses = await apiFetch('/course');
                allCourses = courses || [];
            } catch (err) {
                console.error('Failed to load courses:', err);
                // ── DEMO DATA (remove when wired to real API) ──
                allCourses = DEMO_COURSES;
            }

            // If already finalized, populate selectedIds with the student's courses
            if (IS_FINISH) {
                const studentCourses = @json($courses ?? []);
                studentCourses.forEach(course => {
                    selectedIds.add(course.id);
                });
            }

            renderCourseList();
            updateCalendar();
            updateCredits();
            renderLegend();
            if (!IS_FINISH) renderSuggested();
        }

        // ─────────────────────────────────────────────────────
        // SELECTION
        // ─────────────────────────────────────────────────────
        let currentDetailCourseId = null;

        function toggleSelect(courseId) {
            // Prevent selection changes if already finalized
            if (IS_FINISH) {
                alert('Your schedule has been finalized. Use the reset button to make changes.');
                return;
            }

            if (selectedIds.has(courseId)) {
                selectedIds.delete(courseId);
                removeCourseEvents(courseId);
            } else {
                selectedIds.add(courseId);
                const course = allCourses.find(c => c.id === courseId);
                if (course) courseToFcEvents(course).forEach(ev => calendar.addEvent(ev));
            }
            renderCourseList();
            updateCredits();
            renderLegend();
            document.getElementById('selectedCount').textContent = selectedIds.size;

            // Update submit button state
            if (!IS_FINISH) {
                const submitBtn = document.getElementById('submitBtn');
                if (selectedIds.size > 0) {
                    submitBtn.className =
                        'w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg transition-colors border bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700';
                    submitBtn.disabled = false;
                } else {
                    submitBtn.className =
                        'w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg transition-colors border bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed';
                    submitBtn.disabled = true;
                }
            }
        }

        function getCourseById(courseId) {
            return allCourses.find(c => c.id === courseId || String(c.id) === String(courseId));
        }

        function openCourseDetail(courseId) {
            const course = getCourseById(courseId);
            if (!course) return;
            currentDetailCourseId = courseId;

            document.getElementById('courseDetailTitle').textContent = course.title || 'Course details';
            document.getElementById('courseDetailCode').textContent = course.code || 'No code';
            document.getElementById('courseDetailCategory').textContent = course.category || 'Uncategorized';
            document.getElementById('courseDetailCredits').textContent = `${course.credits || 0} credits`;
            document.getElementById('courseDetailTeacher').textContent =
                `Teacher: ${course.teacher?.name || course.teacher_name || course.instructor || 'TBA'}`;

            document.getElementById('courseDetailDescription').textContent = course.description ||
                'No additional description available.';

            const scheduleContainer = document.getElementById('courseDetailSchedule');
            scheduleContainer.innerHTML = (course.schedules || []).length ?
                course.schedules.map((sch) => {
                    const day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][sch.day_of_week] ||
                        `Day ${sch.day_of_week}`;
                    const ss = SESSIONS[sch.start_session_id];
                    const es = SESSIONS[sch.end_session_id];
                    const time = ss && es ? `${ss.start} - ${es.end}` : 'Time unavailable';
                    return `<div class="rounded-2xl bg-gray-50 p-3 text-sm text-gray-700">
                      <div class="font-semibold text-gray-900">${day} · ${time}</div>
                      <div class="text-[11px] text-gray-500">Weeks ${sch.start_week}–${sch.end_week}</div>
                    </div>`;
                }).join('') :
                `<p class="text-sm text-gray-500">No schedule information available.</p>`;

            const actionBtn = document.getElementById('courseDetailActionBtn');
            if (IS_FINISH) {
                const selected = selectedIds.has(courseId);
                actionBtn.textContent = selected ? 'Selected' : 'Not selected';
                actionBtn.disabled = true;
                actionBtn.className =
                    'w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed bg-gray-200 text-gray-500 border border-transparent';
                actionBtn.onclick = null;
            } else {
                const selected = selectedIds.has(courseId);
                actionBtn.textContent = selected ? 'Remove from schedule' : 'Add to schedule';
                actionBtn.className = selected ?
                    'w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors' :
                    'w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors';
                actionBtn.disabled = false;
                actionBtn.onclick = () => {
                    toggleSelect(courseId);
                    closeCourseDetailModal();
                };
            }

            document.getElementById('courseDetailModal').classList.add('open');
        }

        function closeCourseDetailModal() {
            document.getElementById('courseDetailModal').classList.remove('open');
        }

        function removeCourseEvents(courseId) {
            calendar.getEvents()
                .filter(e => e.extendedProps.courseId === courseId)
                .forEach(e => e.remove());
        }

        function updateCalendar() {
            calendar.getEvents().forEach(e => e.remove());
            allCourses.filter(c => selectedIds.has(c.id)).forEach(course => {
                courseToFcEvents(course).forEach(ev => calendar.addEvent(ev));
            });
        }

        // ─────────────────────────────────────────────────────
        // RENDER COURSE LIST
        // ─────────────────────────────────────────────────────
        const BADGE_STYLES = {
            'Major': 'bg-indigo-50 text-indigo-700',
            'General Ed.': 'bg-emerald-50 text-emerald-700',
            'Internship': 'bg-amber-50 text-amber-700',
        };

        function renderCourseList() {
            const list = document.getElementById('courseList');
            let courses = allCourses;

            if (searchQuery) {
                const q = searchQuery.toLowerCase();
                courses = courses.filter(c =>
                    c.title.toLowerCase().includes(q) ||
                    (c.code || '').toLowerCase().includes(q) ||
                    (c.category || '').toLowerCase().includes(q)
                );
            }

            if (currentTab === 'selected') {
                courses = courses.filter(c => selectedIds.has(c.id));
            }

            if (!courses.length) {
                list.innerHTML = `<div class="flex flex-col items-center justify-center h-32 text-center text-gray-400">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
          <p class="text-sm">${currentTab === 'selected' ? 'No courses selected' : 'No courses found'}</p>
        </div>`;
                return;
            }

            list.innerHTML = courses.map(course => {
                const isSelected = selectedIds.has(course.id);
                const color = seededColor(course.id);
                const badge = BADGE_STYLES[course.category] || 'bg-gray-100 text-gray-600';

                return `
          <div class="course-card ${isSelected ? 'selected' : ''} rounded-lg px-3 py-2.5 cursor-pointer"
               style="--course-color: ${color}"
               onclick="toggleSelect(${course.id})">
            <div class="flex items-start justify-between gap-2">
              <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm leading-snug truncate">${course.title}</p>
                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                  ${course.code ? `<span class="text-[11px] text-gray-400 font-medium">${course.code}</span>` : ''}
                  ${course.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${course.category}</span>` : ''}
                </div>
                <p class="text-[11px] text-gray-400 mt-1">${course.credits || 0} Credits</p>
              </div>
              <button class="flex-shrink-0 w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all mt-0.5"
                      style="${isSelected ? `background:${color}; border-color:${color}` : 'background:white; border-color:#d1d5db'}"
                      onclick="event.stopPropagation(); toggleSelect(${course.id})">
                ${isSelected
                  ? `<svg class="check-pop" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
                  : `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>`
                }
              </button>
            </div>
            <div class="mt-3 flex items-center justify-between gap-3">
              <button onclick="event.stopPropagation(); openCourseDetail(${course.id})"
                class="text-xs font-semibold text-indigo-600 border border-indigo-200 px-2 py-1 rounded-lg hover:bg-indigo-50 transition-colors">
                Detail
              </button>
              <span class="text-[11px] ${isSelected ? 'text-indigo-700' : 'text-gray-400'}">
                ${isSelected ? 'Selected' : 'Tap the circle to add'}
              </span>
            </div>
          </div>`;
            }).join('');
        }

        // ─────────────────────────────────────────────────────
        // CREDIT SUMMARY
        // ─────────────────────────────────────────────────────
        function updateCredits() {
            const selected = allCourses.filter(c => selectedIds.has(c.id));
            const total = selected.reduce((s, c) => s + (c.credits || 0), 0);
            const pct = Math.min(100, Math.round((total / CREDIT_GOAL) * 100));

            document.getElementById('totalCreditsNum').textContent = total;
            document.getElementById('totalProgressBar').style.width = pct + '%';

            const numEl = document.getElementById('totalCreditsNum');
            const barEl = document.getElementById('totalProgressBar');
            if (total >= CREDIT_GOAL) {
                numEl.className = 'text-4xl font-bold text-emerald-500';
                barEl.className = 'progress-fill bg-emerald-500';
            } else {
                numEl.className = 'text-4xl font-bold text-red-500';
                barEl.className = 'progress-fill bg-red-500';
            }

            const gap = CREDIT_GOAL - total;
            const gapEl = document.getElementById('creditGapText');
            if (gap <= 0) {
                gapEl.textContent = 'Credit goal reached!';
                document.getElementById('creditGapMsg').querySelector('svg').style.display = 'none';
                document.getElementById('creditGapMsg').style.color = '#059669';
            } else {
                gapEl.textContent = `You need ${gap} more credits`;
                document.getElementById('creditGapMsg').querySelector('svg').style.display = '';
                document.getElementById('creditGapMsg').style.color = '';
            }

            // Category breakdown
            const breakdown = document.getElementById('categoryBreakdown');
            breakdown.innerHTML = Object.entries(CATEGORIES).map(([cat, cfg]) => {
                const earned = selected.filter(c => c.category === cat).reduce((s, c) => s + (c.credits || 0), 0);
                const p = Math.min(100, Math.round((earned / cfg.goal) * 100));
                return `
          <div>
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs text-gray-600">${cat}</span>
              <div class="flex items-center gap-1">
                <span class="text-xs font-semibold text-gray-900">${earned} / ${cfg.goal}</span>
                <button class="text-gray-300 hover:text-gray-500 transition-colors">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
              </div>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width:${p}%; background:${earned >= cfg.goal ? '#059669' : cfg.color}"></div>
            </div>
          </div>`;
            }).join('');

            // Warning banner
            const warn = document.getElementById('creditWarning');
            if (gap > 0) {
                warn.classList.remove('hidden');
                warn.classList.add('flex');
                document.getElementById('warningBody').textContent =
                    `You need ${gap} more credits in total. Please add more courses or adjust your selections.`;
            } else {
                warn.classList.add('hidden');
                warn.classList.remove('flex');
            }
        }

        // ─────────────────────────────────────────────────────
        // LEGEND
        // ─────────────────────────────────────────────────────
        function renderLegend() {
            const legend = document.getElementById('legend');
            const selected = allCourses.filter(c => selectedIds.has(c.id));
            if (!selected.length) {
                legend.innerHTML = '';
                return;
            }
            legend.innerHTML = selected.map(c => `
        <div class="flex items-center gap-1.5">
          <div class="legend-dot" style="background:${seededColor(c.id)}"></div>
          <span class="text-xs text-gray-500">${c.title}</span>
        </div>`).join('');
        }

        // ─────────────────────────────────────────────────────
        // SUGGESTED (stub — wire to real recommendations API)
        // ─────────────────────────────────────────────────────
        function renderSuggested() {
            const unselected = allCourses.filter(c => !selectedIds.has(c.id)).slice(0, 3);
            const el = document.getElementById('suggestedList');
            if (!unselected.length) {
                el.innerHTML = '<p class="text-xs text-gray-400">All courses selected.</p>';
                return;
            }
            el.innerHTML = unselected.map(c => {
                const badge = BADGE_STYLES[c.category] || 'bg-gray-100 text-gray-600';
                return `
          <div class="border border-gray-100 rounded-xl p-3 hover:border-gray-200 transition-colors">
            <div class="flex items-start justify-between gap-2">
              <div class="flex-1">
                <div class="flex items-center gap-1.5 mb-0.5">
                  <div class="w-1 h-10 rounded-full flex-shrink-0" style="background:${seededColor(c.id)}"></div>
                  <div>
                    <p class="text-sm font-medium text-gray-900 leading-snug">${c.title}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                      ${c.code ? `<span class="text-[11px] text-gray-400">${c.code}</span>` : ''}
                      ${c.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${c.category}</span>` : ''}
                    </div>
                    <p class="text-[11px] text-gray-400 mt-0.5">${c.credits || 0} Credits</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-3 flex items-center justify-between gap-2">
              <button onclick="event.stopPropagation(); openCourseDetail(${c.id})"
                class="text-xs font-semibold text-gray-600 border border-gray-200 px-2.5 py-1 rounded-lg hover:bg-gray-50 transition-colors whitespace-nowrap">
                Detail
              </button>
              <button onclick="event.stopPropagation(); toggleSelect(${c.id})"
                class="flex-shrink-0 flex items-center gap-1 text-xs font-semibold text-indigo-600 border border-indigo-200 px-2.5 py-1 rounded-lg hover:bg-indigo-50 transition-colors whitespace-nowrap">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add
              </button>
            </div>
          </div>`;
            }).join('');
        }

        // ─────────────────────────────────────────────────────
        // TABS & SEARCH
        // ─────────────────────────────────────────────────────
        function switchTab(tab) {
            currentTab = tab;
            document.getElementById('tabAll').className = tab === 'all' ?
                'tab-active flex-1 text-sm font-medium py-2 transition-colors' :
                'tab-inactive flex-1 text-sm font-medium py-2 transition-colors';
            document.getElementById('tabSelected').className = tab === 'selected' ?
                'tab-active flex-1 text-sm font-medium py-2 transition-colors' :
                'tab-inactive flex-1 text-sm font-medium py-2 transition-colors';
            renderCourseList();
        }

        document.getElementById('courseSearch').addEventListener('input', e => {
            searchQuery = e.target.value;
            renderCourseList();
        });

        // ─────────────────────────────────────────────────────
        // SUBMIT & RESET HANDLERS
        // ─────────────────────────────────────────────────────
        function handleSubmit() {
            if (selectedIds.size === 0) {
                alert('Please select at least one course before finalizing.');
                return;
            }

            // Populate hidden input with selected course IDs
            const input = document.getElementById('selectedCoursesInput');
            input.innerHTML = Array.from(selectedIds)
                .map(id => `<input type="hidden" name="selectedCourses[]" value="${id}" />`)
                .join('');

            // Submit the form
            document.getElementById('submitForm').submit();
        }

        // ─────────────────────────────────────────────────────
        // FULLCALENDAR
        // ─────────────────────────────────────────────────────
        const calendarEl = document.getElementById('calendar');
        const today = new Date();
        const initialCalendarDate = (today >= SEMESTER_START && today <= SEMESTER_END) ?
            isoDate(today) :
            isoDate(SEMESTER_START);
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            initialDate: initialCalendarDate,
            allDaySlot: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '22:00:00',
            scrollTime: '08:00:00',
            height: 'auto',
            editable: false,
            selectable: false,
            nowIndicator: true,
            validRange: {
                start: isoDate(SEMESTER_START),
                end: isoDate(new Date(SEMESTER_END.getTime() + 86400000)),
            },
            dayHeaderContent(arg) {
                const week = semesterWeekForDate(arg.date);
                return {
                    html: week ?
                        `${arg.text}<div class="text-[10px] text-gray-400">W${week}</div>` : arg.text,
                };
            },
            dayCellContent(arg) {
                const week = semesterWeekForDate(arg.date);
                return {
                    html: week ?
                        `${arg.dayNumberText}<div style="font-size:0.72em;color:#6b7280;margin-top:2px;">W${week}</div>` :
                        arg.dayNumberText,
                };
            },
            eventClick(info) {
                const courseId = info.event.extendedProps.courseId;
                if (courseId) openCourseDetail(courseId);
            },
            // Empty state text
            noEventsContent: 'Select courses from the left to see them here.',
        });

        calendar.render();

        // ─────────────────────────────────────────────────────
        // DEMO DATA (remove once API is wired up)
        // ─────────────────────────────────────────────────────
        const DEMO_COURSES = [{
                id: 1,
                title: 'Data Structures',
                code: 'CS201',
                category: 'Major',
                credits: 3,
                schedules: [{
                    id: 1,
                    day_of_week: 4,
                    start_session_id: 4,
                    end_session_id: 4,
                    start_week: 1,
                    end_week: 19
                }]
            },
            {
                id: 2,
                title: 'Discrete Mathematics',
                code: 'MATH201',
                category: 'General Ed.',
                credits: 3,
                schedules: [{
                        id: 2,
                        day_of_week: 1,
                        start_session_id: 2,
                        end_session_id: 2,
                        start_week: 1,
                        end_week: 19
                    },
                    {
                        id: 3,
                        day_of_week: 3,
                        start_session_id: 2,
                        end_session_id: 2,
                        start_week: 1,
                        end_week: 19
                    },
                    {
                        id: 4,
                        day_of_week: 5,
                        start_session_id: 2,
                        end_session_id: 2,
                        start_week: 1,
                        end_week: 19
                    },
                ]
            },
            {
                id: 3,
                title: 'Technical Writing',
                code: 'ENG101',
                category: 'General Ed.',
                credits: 2,
                schedules: [{
                        id: 5,
                        day_of_week: 1,
                        start_session_id: 6,
                        end_session_id: 6,
                        start_week: 1,
                        end_week: 19
                    },
                    {
                        id: 6,
                        day_of_week: 3,
                        start_session_id: 6,
                        end_session_id: 6,
                        start_week: 1,
                        end_week: 19
                    },
                    {
                        id: 7,
                        day_of_week: 5,
                        start_session_id: 6,
                        end_session_id: 6,
                        start_week: 1,
                        end_week: 19
                    },
                ]
            },
            {
                id: 4,
                title: 'Database Systems',
                code: 'CS202',
                category: 'Major',
                credits: 3,
                schedules: [{
                        id: 8,
                        day_of_week: 2,
                        start_session_id: 9,
                        end_session_id: 10,
                        start_week: 1,
                        end_week: 19
                    },
                    {
                        id: 9,
                        day_of_week: 4,
                        start_session_id: 9,
                        end_session_id: 10,
                        start_week: 1,
                        end_week: 19
                    },
                ]
            },
            {
                id: 5,
                title: 'Web Development',
                code: 'CS203',
                category: 'Major',
                credits: 3,
                schedules: [{
                    id: 10,
                    day_of_week: 2,
                    start_session_id: 3,
                    end_session_id: 4,
                    start_week: 1,
                    end_week: 19
                }]
            },
            {
                id: 6,
                title: 'Entrepreneurship',
                code: 'BUS101',
                category: 'General Ed.',
                credits: 2,
                schedules: [{
                    id: 11,
                    day_of_week: 3,
                    start_session_id: 5,
                    end_session_id: 6,
                    start_week: 1,
                    end_week: 19
                }]
            },
            {
                id: 7,
                title: 'Software Engineering',
                code: 'CS301',
                category: 'Major',
                credits: 3,
                schedules: [{
                    id: 12,
                    day_of_week: 5,
                    start_session_id: 7,
                    end_session_id: 8,
                    start_week: 1,
                    end_week: 19
                }]
            },
        ];

        // ─────────────────────────────────────────────────────
        // INIT
        // ─────────────────────────────────────────────────────
        loadCourses();
    </script>
</body>

</html>
