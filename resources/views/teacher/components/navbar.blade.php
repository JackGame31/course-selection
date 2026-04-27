<!-- Top Navigation Bar -->
<nav
    class="fixed top-0 left-0 right-0 h-14 bg-[#fdfcf9] border-b border-[#e8e3db] flex items-center px-6 gap-4 z-50 shadow-sm">
    <!-- Logo -->
    <div class="flex items-center gap-2.5 mr-8">
        <div class="w-8 h-8 bg-[#c0392b] rounded-lg flex items-center justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <rect width="16" height="16" rx="4" fill="currentColor" />
                <text x="8" y="11" font-size="8" font-weight="bold" text-anchor="middle" fill="white">T</text>
            </svg>
        </div>
        <span class="font-serif font-semibold text-[#1a1714] text-[17px]">EduPlan</span>
    </div>

    <!-- Navigation Links -->
    <div class="flex items-center gap-1 text-sm">
        <a href="" {{-- {{ route('teacher.dashboard') }} --}}
            class="px-3 py-1.5 text-[#6b6560] hover:text-[#1a1714] rounded-md hover:bg-[#f7f4ef] transition-colors">Dashboard</a>
        <a href="{{ route('teacher.calendar') }}"
            class="px-3 py-1.5 text-[#1a1714] rounded-md bg-[#f7f4ef] transition-colors font-medium">Calendar</a>
    </div>

    <!-- Right Actions -->
    <div class="ml-auto flex items-center gap-3">
        <!-- Notification Bell -->
        <button class="relative p-2 text-[#6b6560] hover:text-[#1a1714] transition-colors">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor"
                stroke-width="1.5">
                <path
                    d="M14 10V6a4 4 0 0 0-8 0v4M3 14h12c.55 0 1 .45 1 1v.5c0 .28-.22.5-.5.5h-13c-.28 0-.5-.22-.5-.5V15c0-.55.45-1 1-1Z" />
                <circle cx="14" cy="6" r="1.5" fill="currentColor" />
            </svg>
        </button>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('teacher.logout') }}" class="inline">
            @method('DELETE')
            @csrf
            <button type="submit"
                class="px-3 py-1.5 text-[#6b6560] hover:text-[#c0392b] rounded-md hover:bg-[#fef1f0] transition-colors text-sm font-medium">
                Logout
            </button>
        </form>

        <!-- User Profile -->
        <div class="flex items-center gap-2 cursor-pointer ml-2">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-[#c0392b] to-[#a93226] flex items-center justify-center text-white text-sm font-semibold">
                {{ strtoupper(substr(auth('admin')->user()->name ?? 'T', 0, 1)) }}
            </div>
            <div class="text-sm hidden sm:block">
                <p class="font-medium text-[#1a1714]">{{ auth('admin')->user()->name ?? 'Teacher' }}</p>
                <p class="text-xs text-[#6b6560]">Administrator</p>
            </div>
        </div>
    </div>
</nav>
