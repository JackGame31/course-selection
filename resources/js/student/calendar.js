// ─────────────────────────────────────────────────────
// STATIC DATA (same schema as admin side)
// ─────────────────────────────────────────────────────
const SEMESTER_START = new Date("2026-03-02T00:00:00");
const SEMESTER_END = new Date("2026-07-12T23:59:59");
const SESSIONS = {
    1: {
        start: "08:00",
        end: "08:50",
    },
    2: {
        start: "08:55",
        end: "09:45",
    },
    3: {
        start: "10:15",
        end: "11:05",
    },
    4: {
        start: "11:10",
        end: "12:00",
    },
    5: {
        start: "14:00",
        end: "14:50",
    },
    6: {
        start: "14:55",
        end: "15:45",
    },
    7: {
        start: "16:15",
        end: "17:05",
    },
    8: {
        start: "17:10",
        end: "18:00",
    },
    9: {
        start: "18:45",
        end: "19:35",
    },
    10: {
        start: "19:40",
        end: "20:30",
    },
    11: {
        start: "20:30",
        end: "21:20",
    },
};
// ─────────────────────────────────────────────────────
// COLOR — same seeded PRNG as admin side (no color in DB)
// For student view, seed only on course.id (no admin_id dependency)
// ─────────────────────────────────────────────────────
const PALETTE = [
    "#2563eb",
    "#7c3aed",
    "#059669",
    "#d97706",
    "#dc2626",
    "#0891b2",
    "#65a30d",
    "#9333ea",
    "#c2410c",
    "#0284c7",
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
const STUDENT_ID = window.studentCalendarData?.studentId ?? null;
const IS_FINISH = window.studentCalendarData?.isFinish ?? false;
const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]').content;
let allCourses = []; // All courses from API
let selectedIds = new Set(); // Selected course IDs
let currentTab = "all";
let searchQuery = "";
// Credit config (adjust per university rules)
const CREDIT_GOAL = 15;
const CATEGORIES = {
    Major: {
        goal: 6,
        color: "#2563eb",
    },
    "General Ed.": {
        goal: 6,
        color: "#059669",
    },
    Internship: {
        goal: 3,
        color: "#d97706",
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
    (course.schedules || []).forEach((sch) => {
        const ss = SESSIONS[sch.start_session_id];
        const es = SESSIONS[sch.end_session_id];
        if (!ss || !es) return;
        const [sh, sm] = ss.start.split(":").map(Number);
        const [eh, em] = es.end.split(":").map(Number);
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
                textColor: "#fff",
                extendedProps: {
                    courseId: course.id,
                    credits: course.credits || 0,
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
    const method = (options.method || "GET").toUpperCase();
    const headers = {
        Accept: "application/json",
    };
    if (method !== "GET") {
        headers["Content-Type"] = "application/json";
        headers["X-CSRF-TOKEN"] = csrfToken();
    }
    const res = await fetch(`/api${path}`, {
        ...options,
        headers,
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
        const courses = await apiFetch("/course");
        allCourses = courses || [];
    } catch (err) {
        console.error("Failed to load courses:", err);
        // ── DEMO DATA (remove when wired to real API) ──
        allCourses = DEMO_COURSES;
    }
    // If already finalized, populate selectedIds with the student's courses
    if (IS_FINISH) {
        const studentCourses = window.studentCalendarData?.courses ?? [];
        studentCourses.forEach((course) => {
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
function getCourseById(courseId) {
    return allCourses.find(
        (c) => c.id === courseId || String(c.id) === String(courseId),
    );
}
function openCourseDetail(courseId) {
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
        const selected = selectedIds.has(courseId);
        actionBtn.textContent = selected ? "Selected" : "Not selected";
        actionBtn.disabled = true;
        actionBtn.className =
            "w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed bg-gray-200 text-gray-500 border border-transparent";
        actionBtn.onclick = null;
    } else {
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
function closeCourseDetailModal() {
    document.getElementById("courseDetailModal").classList.remove("open");
}
function removeCourseEvents(courseId) {
    calendar
        .getEvents()
        .filter((e) => e.extendedProps.courseId === courseId)
        .forEach((e) => e.remove());
}
function updateCalendar() {
    calendar.getEvents().forEach((e) => e.remove());
    allCourses
        .filter((c) => selectedIds.has(c.id))
        .forEach((course) => {
            courseToFcEvents(course).forEach((ev) => calendar.addEvent(ev));
        });
}
// ─────────────────────────────────────────────────────
// RENDER COURSE LIST
// ─────────────────────────────────────────────────────
const BADGE_STYLES = {
    Major: "bg-indigo-50 text-indigo-700",
    "General Ed.": "bg-emerald-50 text-emerald-700",
    Internship: "bg-amber-50 text-amber-700",
};
function renderCourseList() {
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
// ─────────────────────────────────────────────────────
// CREDIT SUMMARY
// ─────────────────────────────────────────────────────
function updateCredits() {
    const selected = allCourses.filter((c) => selectedIds.has(c.id));
    const total = selected.reduce((s, c) => s + (c.credits || 0), 0);
    const pct = Math.min(100, Math.round((total / CREDIT_GOAL) * 100));
    document.getElementById("totalCreditsNum").textContent = total;
    document.getElementById("totalProgressBar").style.width = pct + "%";
    const numEl = document.getElementById("totalCreditsNum");
    const barEl = document.getElementById("totalProgressBar");
    if (total >= CREDIT_GOAL) {
        numEl.className = "text-4xl font-bold text-emerald-500";
        barEl.className = "progress-fill bg-emerald-500";
    } else {
        numEl.className = "text-4xl font-bold text-red-500";
        barEl.className = "progress-fill bg-red-500";
    }
    const gap = CREDIT_GOAL - total;
    const gapEl = document.getElementById("creditGapText");
    if (gap <= 0) {
        gapEl.textContent = "Credit goal reached!";
        document
            .getElementById("creditGapMsg")
            .querySelector("svg").style.display = "none";
        document.getElementById("creditGapMsg").style.color = "#059669";
    } else {
        gapEl.textContent = `You need ${gap} more credits`;
        document
            .getElementById("creditGapMsg")
            .querySelector("svg").style.display = "";
        document.getElementById("creditGapMsg").style.color = "";
    }
    // Category breakdown
    const breakdown = document.getElementById("categoryBreakdown");
    breakdown.innerHTML = Object.entries(CATEGORIES)
        .map(([cat, cfg]) => {
            const earned = selected
                .filter((c) => c.category === cat)
                .reduce((s, c) => s + (c.credits || 0), 0);
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
      <div class="progress-fill" style="width:${p}%; background:${earned >= cfg.goal ? "#059669" : cfg.color}"></div>
    </div>
  </div>`;
        })
        .join("");
    // Warning banner
    const warn = document.getElementById("creditWarning");
    if (gap > 0) {
        warn.classList.remove("hidden");
        warn.classList.add("flex");
        document.getElementById("warningBody").textContent =
            `You need ${gap} more credits in total. Please add more courses or adjust your selections.`;
    } else {
        warn.classList.add("hidden");
        warn.classList.remove("flex");
    }
}
// ─────────────────────────────────────────────────────
// LEGEND
// ─────────────────────────────────────────────────────
function renderLegend() {
    const legend = document.getElementById("legend");
    const selected = allCourses.filter((c) => selectedIds.has(c.id));
    if (!selected.length) {
        legend.innerHTML = "";
        return;
    }
    legend.innerHTML = selected
        .map(
            (c) => `
<div class="flex items-center gap-1.5">
  <div class="legend-dot" style="background:${seededColor(c.id)}"></div>
  <span class="text-xs text-gray-500">${c.title}</span>
</div>`,
        )
        .join("");
}
// ─────────────────────────────────────────────────────
// SUGGESTED (stub — wire to real recommendations API)
// ─────────────────────────────────────────────────────
function renderSuggested() {
    const unselected = allCourses
        .filter((c) => !selectedIds.has(c.id))
        .slice(0, 3);
    const el = document.getElementById("suggestedList");
    if (!unselected.length) {
        el.innerHTML =
            '<p class="text-xs text-gray-400">All courses selected.</p>';
        return;
    }
    el.innerHTML = unselected
        .map((c) => {
            const badge =
                BADGE_STYLES[c.category] || "bg-gray-100 text-gray-600";
            return `
  <div class="border border-gray-100 rounded-xl p-3 hover:border-gray-200 transition-colors">
    <div class="flex items-start justify-between gap-2">
      <div class="flex-1">
        <div class="flex items-center gap-1.5 mb-0.5">
          <div class="w-1 h-10 rounded-full flex-shrink-0" style="background:${seededColor(c.id)}"></div>
          <div>
            <p class="text-sm font-medium text-gray-900 leading-snug">${c.title}</p>
            <div class="flex items-center gap-1.5 mt-0.5">
              ${c.code ? `<span class="text-[11px] text-gray-400">${c.code}</span>` : ""}
              ${c.category ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded ${badge}">${c.category}</span>` : ""}
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
        })
        .join("");
}
// ─────────────────────────────────────────────────────
// TABS & SEARCH
// ─────────────────────────────────────────────────────
function switchTab(tab) {
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
document.getElementById("courseSearch").addEventListener("input", (e) => {
    searchQuery = e.target.value;
    renderCourseList();
});
// ─────────────────────────────────────────────────────
// SUBMIT & RESET HANDLERS
// ─────────────────────────────────────────────────────
function handleSubmit() {
    if (selectedIds.size === 0) {
        alert("Please select at least one course before finalizing.");
        return;
    }
    // Populate hidden input with selected course IDs
    const input = document.getElementById("selectedCoursesInput");
    input.innerHTML = Array.from(selectedIds)
        .map(
            (id) =>
                `<input type="hidden" name="selectedCourses[]" value="${id}" />`,
        )
        .join("");
    // Submit the form
    document.getElementById("submitForm").submit();
}
// ─────────────────────────────────────────────────────
// FULLCALENDAR
// ─────────────────────────────────────────────────────
const calendarEl = document.getElementById("calendar");
const today = new Date();
const initialCalendarDate =
    today >= SEMESTER_START && today <= SEMESTER_END
        ? isoDate(today)
        : isoDate(SEMESTER_START);
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "timeGridWeek",
    initialDate: initialCalendarDate,
    allDaySlot: false,
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    slotMinTime: "08:00:00",
    slotMaxTime: "22:00:00",
    scrollTime: "08:00:00",
    height: "auto",
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
            html: week
                ? `${arg.text}<div class="text-[10px] text-gray-400">W${week}</div>`
                : arg.text,
        };
    },
    dayCellContent(arg) {
        const week = semesterWeekForDate(arg.date);
        return {
            html: week
                ? `${arg.dayNumberText}<div style="font-size:0.72em;color:#6b7280;margin-top:2px;">W${week}</div>`
                : arg.dayNumberText,
        };
    },
    eventClick(info) {
        const courseId = info.event.extendedProps.courseId;
        if (courseId) openCourseDetail(courseId);
    },
    // Empty state text
    noEventsContent: "Select courses from the left to see them here.",
});
calendar.render();
// ─────────────────────────────────────────────────────
// DEMO DATA (remove once API is wired up)
// ─────────────────────────────────────────────────────
const DEMO_COURSES = [
    {
        id: 1,
        title: "Data Structures",
        code: "CS201",
        category: "Major",
        credits: 3,
        schedules: [
            {
                id: 1,
                day_of_week: 4,
                start_session_id: 4,
                end_session_id: 4,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 2,
        title: "Discrete Mathematics",
        code: "MATH201",
        category: "General Ed.",
        credits: 3,
        schedules: [
            {
                id: 2,
                day_of_week: 1,
                start_session_id: 2,
                end_session_id: 2,
                start_week: 1,
                end_week: 19,
            },
            {
                id: 3,
                day_of_week: 3,
                start_session_id: 2,
                end_session_id: 2,
                start_week: 1,
                end_week: 19,
            },
            {
                id: 4,
                day_of_week: 5,
                start_session_id: 2,
                end_session_id: 2,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 3,
        title: "Technical Writing",
        code: "ENG101",
        category: "General Ed.",
        credits: 2,
        schedules: [
            {
                id: 5,
                day_of_week: 1,
                start_session_id: 6,
                end_session_id: 6,
                start_week: 1,
                end_week: 19,
            },
            {
                id: 6,
                day_of_week: 3,
                start_session_id: 6,
                end_session_id: 6,
                start_week: 1,
                end_week: 19,
            },
            {
                id: 7,
                day_of_week: 5,
                start_session_id: 6,
                end_session_id: 6,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 4,
        title: "Database Systems",
        code: "CS202",
        category: "Major",
        credits: 3,
        schedules: [
            {
                id: 8,
                day_of_week: 2,
                start_session_id: 9,
                end_session_id: 10,
                start_week: 1,
                end_week: 19,
            },
            {
                id: 9,
                day_of_week: 4,
                start_session_id: 9,
                end_session_id: 10,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 5,
        title: "Web Development",
        code: "CS203",
        category: "Major",
        credits: 3,
        schedules: [
            {
                id: 10,
                day_of_week: 2,
                start_session_id: 3,
                end_session_id: 4,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 6,
        title: "Entrepreneurship",
        code: "BUS101",
        category: "General Ed.",
        credits: 2,
        schedules: [
            {
                id: 11,
                day_of_week: 3,
                start_session_id: 5,
                end_session_id: 6,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
    {
        id: 7,
        title: "Software Engineering",
        code: "CS301",
        category: "Major",
        credits: 3,
        schedules: [
            {
                id: 12,
                day_of_week: 5,
                start_session_id: 7,
                end_session_id: 8,
                start_week: 1,
                end_week: 19,
            },
        ],
    },
];
// ─────────────────────────────────────────────────────
// INIT
// ─────────────────────────────────────────────────────
loadCourses();

// Expose functions to global scope for inline event handlers
window.openCourseDetail = openCourseDetail;
window.closeCourseDetailModal = closeCourseDetailModal;
window.toggleSelect = toggleSelect;
window.switchTab = switchTab;
window.handleSubmit = handleSubmit;
