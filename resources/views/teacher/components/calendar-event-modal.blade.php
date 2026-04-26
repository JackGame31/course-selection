<div id="eventModal"
    class="modal-overlay fixed inset-0 bg-black/45 backdrop-blur-sm z-[9999] items-center justify-center">
    <div
        class="modal bg-[#fdfcf9] rounded-2xl w-[500px] max-w-[calc(100vw-40px)] max-h-[92vh] overflow-y-auto shadow-2xl border border-[#e8e3db]">

        <!-- Modal header -->
        <div
            class="px-6 pt-[22px] pb-[18px] border-b border-[#e8e3db] flex items-center justify-between sticky top-0 bg-[#fdfcf9] z-10">
            <h2 id="modalTitle" class="font-serif text-lg font-semibold">New Course</h2>
            <button onclick="closeModal()"
                class="w-[30px] h-[30px] flex items-center justify-center bg-transparent border-none cursor-pointer text-[#6b6560] rounded-md text-xl leading-none hover:bg-[#f7f4ef] hover:text-[#1a1714] transition-colors">
                &#x2715;
            </button>
        </div>

        <!-- Modal body -->
        <div class="px-6 py-5 flex flex-col gap-4">
            <div id="modalStatus" class="hidden px-3 py-2 rounded-lg text-sm"></div>

            <!-- Title -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-[#6b6560] uppercase tracking-wide">Course Title</label>
                <input type="text" id="evTitle" placeholder="e.g. Calculus I (A)"
                    class="field-input px-3 py-2 border border-[#e8e3db] rounded-lg text-sm text-[#1a1714] bg-[#f7f4ef] w-full transition-colors" />
            </div>

            <!-- Start week -->
            <div class="flex flex-col gap-1.5 max-w-[160px]">
                <label class="text-[11px] font-medium text-[#6b6560] uppercase tracking-wide">Start Week</label>
                <input type="number" id="evStartWeek" min="1" max="19" value="1"
                    class="field-input px-3 py-2 border border-[#e8e3db] rounded-lg text-sm text-[#1a1714] bg-[#f7f4ef] w-full transition-colors" />
                <span id="startWeekHint" class="text-[11px] text-[#6b6560] min-h-[14px]"></span>
            </div>

            <!-- Divider -->
            <div class="h-px bg-[#e8e3db] -mx-6"></div>

            <!-- Schedule rows -->
            <div id="scheduleRows" class="flex flex-col gap-3"></div>

            <!-- Add schedule -->
            <button id="btnAddSchedule" onclick="addScheduleRow()"
                class="flex items-center gap-2 justify-center py-2.5 border border-dashed border-[#c4bfb8] rounded-lg bg-transparent text-[#6b6560] text-sm cursor-pointer transition-all hover:border-[#1a1714] hover:text-[#1a1714] hover:bg-[#f7f4ef]">
                <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                    <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                Add 2nd Class Time
            </button>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 border-t border-[#e8e3db] flex gap-2.5 justify-end sticky bottom-0 bg-[#fdfcf9]">
            <button id="btnDelete" onclick="deleteCourse()" style="display:none"
                class="mr-auto px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer bg-[#fef1f0] text-[#c0392b] border border-[#f5c6c2] transition-colors hover:bg-[#fde0dc] active:scale-[0.97]">
                Delete Course
            </button>
            <button onclick="closeModal()"
                class="px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer bg-[#f7f4ef] text-[#6b6560] border border-[#e8e3db] transition-colors hover:bg-[#ece7df] active:scale-[0.97]">
                Cancel
            </button>
            <button id="btnSave" onclick="saveEvent()"
                class="px-4 py-2.5 rounded-lg text-sm font-medium cursor-pointer bg-[#1a1714] text-white border border-transparent transition-colors hover:bg-[#333] active:scale-[0.97] disabled:opacity-50 disabled:cursor-not-allowed">
                Save
            </button>
        </div>
    </div>
</div>
