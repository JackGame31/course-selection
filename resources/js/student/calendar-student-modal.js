// ── Course Detail Modal ──────────────────────────────
let currentDetailCourseId = null;

export function openCourseDetail(courseId) {
    const course = getCourseById(courseId);
    if (!course) return;
    currentDetailCourseId = courseId;
    document.getElementById("courseDetailTitle").textContent =
        course.title || "Course details";
    document.getElementById("courseDetailCode").textContent =
        course.code || "No code";
    document.getElementById("courseDetailCategory").textContent =
        course.category || "Uncategorized";
    document.getElementById("courseDetailCredits").textContent =
        `${course.credits || 0} credits`;
    document.getElementById("courseDetailTeacher").textContent =
        `Teacher: ${course.teacher?.name || course.teacher_name || course.instructor || "TBA"}`;
    document.getElementById("courseDetailDescription").textContent =
        course.description || "No additional description available.";
    const scheduleContainer = document.getElementById("courseDetailSchedule");
    scheduleContainer.innerHTML = (course.schedules || []).length
        ? course.schedules
              .map((sch) => {
                  const day =
                      ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][
                          sch.day_of_week
                      ] || `Day ${sch.day_of_week}`;
                  const ss = SESSIONS[sch.start_session_id];
                  const es = SESSIONS[sch.end_session_id];
                  const time =
                      ss && es ? `${ss.start} - ${es.end}` : "Time unavailable";
                  return `<div class="rounded-2xl bg-gray-50 p-3 text-sm text-gray-700">
              <div class="font-semibold text-gray-900">${day} · ${time}</div>
              <div class="text-[11px] text-gray-500">Weeks ${sch.start_week}–${sch.end_week}</div>
            </div>`;
              })
              .join("")
        : `<p class="text-sm text-gray-500">No schedule information available.</p>`;
    const actionBtn = document.getElementById("courseDetailActionBtn");
    if (IS_FINISH) {
        actionBtn.style.display = "none"; // hide entirely, no disabled ghost button
    } else {
        actionBtn.style.display = ""; // restore in case modal is reused
        const selected = selectedIds.has(courseId);
        actionBtn.textContent = selected
            ? "Remove from schedule"
            : "Add to schedule";
        actionBtn.className = selected
            ? "w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors"
            : "w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors";
        actionBtn.disabled = false;
        actionBtn.onclick = () => {
            toggleSelect(courseId);
            closeCourseDetailModal();
        };
    }
    document.getElementById("courseDetailModal").classList.add("open");
}

export function closeCourseDetailModal() {
    document.getElementById("courseDetailModal").classList.remove("open");
}
