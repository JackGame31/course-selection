import { seededColor, BADGE_STYLES } from "./calendar-student-colors.js";
import { courseToFcEvents } from "./calendar.js";
import { updateCredits } from "./calendar-student-credits.js";
import { renderLegend } from "./calendar-student-credits.js";

// ── Selection ────────────────────────────────────────
export function toggleSelect(courseId) {
    // Prevent selection changes if already finalized
    if (IS_FINISH) {
        alert(
            "Your schedule has been finalized. Use the reset button to make changes.",
        );
        return;
    }
    if (selectedIds.has(courseId)) {
        selectedIds.delete(courseId);
        removeCourseEvents(courseId);
    } else {
        selectedIds.add(courseId);
        const course = allCourses.find((c) => c.id === courseId);
        if (course)
            courseToFcEvents(course).forEach((ev) => calendar.addEvent(ev));
    }
    renderCourseList();
    updateCredits();
    renderLegend();
    document.getElementById("selectedCount").textContent = selectedIds.size;
    // Update submit button state
    if (!IS_FINISH) {
        const submitBtn = document.getElementById("submitBtn");
        if (selectedIds.size > 0) {
            submitBtn.className =
                "w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg transition-colors border bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700";
            submitBtn.disabled = false;
        } else {
            submitBtn.className =
                "w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg transition-colors border bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed";
            submitBtn.disabled = true;
        }
    }
}

export function getCourseById(courseId) {
    return allCourses.find(
        (c) => c.id === courseId || String(c.id) === String(courseId),
    );
}

export function removeCourseEvents(courseId) {
    calendar
        .getEvents()
        .filter((e) => e.extendedProps.courseId === courseId)
        .forEach((e) => e.remove());
}

export function updateCalendar() {
    calendar.getEvents().forEach((e) => e.remove());
    allCourses
        .filter((c) => selectedIds.has(c.id))
        .forEach((course) => {
            courseToFcEvents(course).forEach((ev) => calendar.addEvent(ev));
        });
}

// ── Render Course List ───────────────────────────────
export function renderCourseList() {
    const list = document.getElementById("courseList");
    let courses = allCourses;
    if (searchQuery) {
        const q = searchQuery.toLowerCase();
        courses = courses.filter(
            (c) =>
                c.title.toLowerCase().includes(q) ||
                (c.code || "").toLowerCase().includes(q) ||
                (c.category || "").toLowerCase().includes(q),
        );
    }
    if (currentTab === "selected") {
        courses = courses.filter((c) => selectedIds.has(c.id));
    }
    if (IS_FINISH) {
        courses = courses.filter((c) => selectedIds.has(c.id));
    }
    if (!courses.length) {
        list.innerHTML = `<div class="flex flex-col items-center justify-center h-32 text-center text-gray-400">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/></svg>
  <p class="text-sm">${currentTab === "selected" ? "No courses selected" : "No courses found"}</p>
</div>`;
        return;
    }
    list.innerHTML = courses
        .map((course) => {
            const isSelected = selectedIds.has(course.id);
            const color = seededColor(course.id);
            const badge =
                BADGE_STYLES[course.category] || "bg-gray-100 text-gray-600";

            // When finalized: card is not clickable for selection, no circle button, no hint text
            if (IS_FINISH) {
                return `
  <div class="course-card ${isSelected ? "selected" : ""} rounded-lg px-3 py-2.5"
       style="--course-color: ${color}">
    <div class="flex items-start justify-between gap-2">
      <div class="flex-1 min-w-0">
        <p class="font-medium text-gray-900 text-sm leading-snug truncate">${course.title}</p>
        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
          ${course.code ? `<span class="text-[11px] text-gray-400 font-medium">${course.code}</span>` : ""}
          ${course.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${course.category}</span>` : ""}
        </div>
        <p class="text-[11px] text-gray-400 mt-1">${course.credits || 0} Credits</p>
      </div>
    </div>
    <div class="mt-3 flex items-center justify-between gap-3">
      <button onclick="event.stopPropagation(); openCourseDetail(${course.id})"
        class="text-xs font-semibold text-indigo-600 border border-indigo-200 px-2 py-1 rounded-lg hover:bg-indigo-50 transition-colors">
        Detail
      </button>
      <span class="text-[11px] ${isSelected ? "text-indigo-700" : "text-gray-400"}">
        ${isSelected ? "Selected" : "Not selected"}
      </span>
    </div>
  </div>`;
            }

            // Normal (non-finalized) card
            return `
  <div class="course-card ${isSelected ? "selected" : ""} rounded-lg px-3 py-2.5 cursor-pointer"
       style="--course-color: ${color}"
       onclick="toggleSelect(${course.id})">
    <div class="flex items-start justify-between gap-2">
      <div class="flex-1 min-w-0">
        <p class="font-medium text-gray-900 text-sm leading-snug truncate">${course.title}</p>
        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
          ${course.code ? `<span class="text-[11px] text-gray-400 font-medium">${course.code}</span>` : ""}
          ${course.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${course.category}</span>` : ""}
        </div>
        <p class="text-[11px] text-gray-400 mt-1">${course.credits || 0} Credits</p>
      </div>
      <button class="flex-shrink-0 w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all mt-0.5"
              style="${isSelected ? `background:${color}; border-color:${color}` : "background:white; border-color:#d1d5db"}"
              onclick="event.stopPropagation(); toggleSelect(${course.id})">
        ${
            isSelected
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
      <span class="text-[11px] ${isSelected ? "text-indigo-700" : "text-gray-400"}">
        ${isSelected ? "Selected" : "Tap the circle to add"}
      </span>
    </div>
  </div>`;
        })
        .join("");
}

// ── Tabs & Search ────────────────────────────────────
export function switchTab(tab) {
    currentTab = tab;
    document.getElementById("tabAll").className =
        tab === "all"
            ? "tab-active flex-1 text-sm font-medium py-2 transition-colors"
            : "tab-inactive flex-1 text-sm font-medium py-2 transition-colors";
    document.getElementById("tabSelected").className =
        tab === "selected"
            ? "tab-active flex-1 text-sm font-medium py-2 transition-colors"
            : "tab-inactive flex-1 text-sm font-medium py-2 transition-colors";
    renderCourseList();
}
