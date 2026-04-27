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
    <div id="courseList" class="course-list flex-1 px-3 py-2 flex flex-col gap-1"></div>

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
