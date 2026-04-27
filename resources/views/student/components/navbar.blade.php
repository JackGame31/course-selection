<nav class="fixed top-0 left-0 right-0 h-14 bg-white border-b border-gray-100 flex items-center px-6 gap-4 z-50 shadow-sm">
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
        <a href="#" class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Dashboard</a>
        <a href="{{ route('student.calendar') }}" class="px-3 py-1.5 text-indigo-600 bg-indigo-50 rounded-md font-medium transition-colors">My Schedule</a>
        <a href="#" class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Courses</a>
        <a href="#" class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">My Enrollments</a>
        <a href="#" class="px-3 py-1.5 text-gray-500 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">Planner</a>
    </div>

    <div class="ml-auto flex items-center gap-3">
        <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors" aria-label="Notifications">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 01-3.46 0" />
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <form method="POST" action="{{ route('logout') }}" class="mr-2">
            @method('DELETE')
            @csrf
            <button type="submit" class="text-sm text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Logout</button>
        </form>
        <div class="flex items-center gap-2 cursor-pointer">
            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>
            <div class="text-sm hidden sm:block">
                <p class="font-medium text-gray-900">{{ auth()->user()->name ?? 'Student' }}</p>
                <p class="text-xs text-gray-500">Student</p>
            </div>
        </div>
    </div>
</nav>
