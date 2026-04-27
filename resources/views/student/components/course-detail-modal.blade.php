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
