// ── Imports ───────────────────────────────────────────
import {
    renderCourseList,
    toggleSelect,
    updateCalendar,
    switchTab,
    getCourseById,
} from "./calendar-student-course-list.js";
import {
    openCourseDetail,
    closeCourseDetailModal,
} from "./calendar-student-modal.js";
import {
    updateCredits,
    renderLegend,
    renderSuggested,
} from "./calendar-student-credits.js";
import { seededColor } from "./calendar-student-colors.js";

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

// ── Make shared variables global ───────────────────────
window.SEMESTER_START = SEMESTER_START;
window.SEMESTER_END = SEMESTER_END;
window.SESSIONS = SESSIONS;
window.STUDENT_ID = STUDENT_ID;
window.IS_FINISH = IS_FINISH;
window.allCourses = allCourses;
window.selectedIds = selectedIds;
window.currentTab = currentTab;
window.searchQuery = searchQuery;
window.CREDIT_GOAL = CREDIT_GOAL;
window.CATEGORIES = CATEGORIES;
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
window.weekToMonday = weekToMonday;
window.scheduleDate = scheduleDate;
window.isoDate = isoDate;
window.semesterWeekForDate = semesterWeekForDate;
// ─────────────────────────────────────────────────────
// COURSE → FC EVENTS
// ─────────────────────────────────────────────────────
export function courseToFcEvents(course) {
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
        console.error("studentCalendar apiFetch failed", {
            path,
            status: res.status,
            body: e,
        });
        throw new Error(e.message || `HTTP ${res.status}`);
    }
    return res.status === 204 ? null : res.json();
}
window.apiFetch = apiFetch;
// ─────────────────────────────────────────────────────
// LOAD COURSES
// ─────────────────────────────────────────────────────
async function loadCourses() {
    try {
        // NOTE: swap with your actual student courses endpoint
        // For a student, you likely want ALL courses, not just one admin's
        const courses = await apiFetch("/course");
        allCourses = courses || [];
        window.allCourses = allCourses;
    } catch (err) {
        console.error("Failed to load courses:", err);
        // ── DEMO DATA (remove when wired to real API) ──
        allCourses = DEMO_COURSES;
        window.allCourses = allCourses;
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
window.calendar = calendar; // Expose to global for debugging and other modules
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
calendar.render();

// Event listeners
const courseSearchEl = document.getElementById("courseSearch");
if (courseSearchEl) {
    courseSearchEl.addEventListener("input", (e) => {
        searchQuery = e.target.value;
        renderCourseList();
    });
}

// Expose functions to global scope for inline event handlers
window.openCourseDetail = openCourseDetail;
window.closeCourseDetailModal = closeCourseDetailModal;
window.toggleSelect = toggleSelect;
window.switchTab = switchTab;
window.handleSubmit = handleSubmit;
window.getCourseById = getCourseById;
