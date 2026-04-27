<div id="rightCredit" class="p-5 border-b border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-900 text-[15px]">Credit Summary</h3>
        <button class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
