// ── Imports ───────────────────────────────────────────
import { seededColor } from "./calendar-colors.js";
import {
    loadTeacherCourses,
    renderCourseList,
    handleCourseCardClick,
    handleEditClick,
    updateCourseCount,
} from "./calendar-course-list.js";
import {
    addScheduleRow,
    removeScheduleRow,
    openCreateModal,
    openEditModal,
    closeModal,
    saveEvent,
    deleteCourse,
} from "./calendar-event-modal.js";

// ── Static academic data ──────────────────────────────
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
const SESSION_PAIRS = [
    {
        label: "Sessions 1-2  (08:00 - 09:45)",
        startId: 1,
        endId: 2,
    },
    {
        label: "Sessions 3-4  (10:15 - 12:00)",
        startId: 3,
        endId: 4,
    },
    {
        label: "Sessions 5-6  (14:00 - 15:45)",
        startId: 5,
        endId: 6,
    },
    {
        label: "Sessions 7-8  (16:15 - 18:00)",
        startId: 7,
        endId: 8,
    },
    {
        label: "Sessions 9-10 (18:45 - 20:30)",
        startId: 9,
        endId: 10,
    },
    {
        label: "Sessions 9-11 (18:45 - 21:20)",
        startId: 9,
        endId: 11,
    },
];
const DAYS = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
];
// ── App state ─────────────────────────────────────────
const ADMIN_ID = window.teacherCalendarData?.adminId;
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const courseStore = new Map();
let editingCourseId = null;
let scheduleRowCount = 0;

// ── Make shared variables global ───────────────────────
window.SEMESTER_START = SEMESTER_START;
window.SEMESTER_END = SEMESTER_END;
window.SESSIONS = SESSIONS;
window.SESSION_PAIRS = SESSION_PAIRS;
window.DAYS = DAYS;
window.ADMIN_ID = ADMIN_ID;
window.csrfToken = csrfToken;
window.courseStore = courseStore;
window.editingCourseId = editingCourseId;
window.scheduleRowCount = scheduleRowCount;
// ── Date/week helpers ─────────────────────────────────
const MONTHS = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];
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
function weekLabel(week) {
    const mon = weekToMonday(week);
    const fri = new Date(mon);
    fri.setDate(fri.getDate() + 4);
    return `Week ${week}: ${mon.getDate()} ${MONTHS[mon.getMonth()]} - ${fri.getDate()} ${MONTHS[fri.getMonth()]}`;
}
function isoDate(date) {
    return date.toISOString().slice(0, 10);
}
function semesterWeekForDate(date) {
    const normalized = new Date(
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
    );
    if (normalized < SEMESTER_START || normalized > SEMESTER_END) return null;
    const diffDays = Math.floor(
        (normalized - SEMESTER_START) / (1000 * 60 * 60 * 24),
    );
    return Math.floor(diffDays / 7) + 1;
}
function jsDateToDayOfWeek(date) {
    return date.getDay();
}
function guessSessionPair(date) {
    const mins = date.getHours() * 60 + date.getMinutes();
    let bestPair = SESSION_PAIRS[0],
        bestDistance = Infinity;
    for (const pair of SESSION_PAIRS) {
        const [ph, pm] = SESSIONS[pair.startId].start.split(":").map(Number);
        const [eh, em] = SESSIONS[pair.endId].end.split(":").map(Number);
        const midpoint = Math.floor((ph * 60 + pm + (eh * 60 + em)) / 2);
        const distance = Math.abs(mins - midpoint);
        if (distance < bestDistance) {
            bestDistance = distance;
            bestPair = pair;
        }
    }
    return bestPair;
}
window.weekToMonday = weekToMonday;
window.scheduleDate = scheduleDate;
window.weekLabel = weekLabel;
window.isoDate = isoDate;
window.semesterWeekForDate = semesterWeekForDate;
window.jsDateToDayOfWeek = jsDateToDayOfWeek;
window.guessSessionPair = guessSessionPair;
// ── Course → FullCalendar events ──────────────────────
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
                },
            });
        }
    });
    return events;
}
// ── API helper ────────────────────────────────────────
async function apiFetch(path, options = {}) {
    const method = (options.method || "GET").toUpperCase();
    const headers = {
        Accept: "application/json",
    };
    if (method !== "GET") {
        headers["Content-Type"] = "application/json";
        headers["X-CSRF-TOKEN"] = csrfToken;
    }
    const res = await fetch(`/api${path}`, {
        ...options,
        headers,
    });
    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || `HTTP ${res.status}`);
    }
    return res.status === 204 ? null : res.json();
}
window.apiFetch = apiFetch;
// ── Calendar store helpers ────────────────────────────
function removeCourseEvents(courseId) {
    calendar
        .getEvents()
        .filter((e) => e.extendedProps.courseId === courseId)
        .forEach((e) => e.remove());
}
function addCourseToCalendar(course) {
    courseStore.set(course.id, course);
    courseToFcEvents(course).forEach((ev) => calendar.addEvent(ev));
}
window.removeCourseEvents = removeCourseEvents;
window.addCourseToCalendar = addCourseToCalendar;
// ── FullCalendar ──────────────────────────────────────
const calendarEl = document.getElementById("calendar");
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "timeGridWeek",
    initialDate: "2026-03-02",
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
                ? `${arg.text}<div style="font-size:0.72em;color:#6b7280;margin-top:2px;">W${week}</div>`
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
    events(fetchInfo, successCallback, failureCallback) {
        apiFetch(`/course?admin_id=${ADMIN_ID}`)
            .then((courses) => {
                if (!courses) {
                    successCallback([]);
                    return;
                }
                courseStore.clear();
                const allEvents = [];
                courses.forEach((course) => {
                    courseStore.set(course.id, course);
                    courseToFcEvents(course).forEach((ev) =>
                        allEvents.push(ev),
                    );
                });
                successCallback(allEvents);
            })
            .catch((err) => {
                console.error(err);
                failureCallback(err);
            });
    },
    dateClick(info) {
        const dow = jsDateToDayOfWeek(info.date);
        const pair = guessSessionPair(info.date);
        const clickedWeek = semesterWeekForDate(info.date) || 1;
        openCreateModal({
            dayOfWeek: dow,
            startSessionId: pair.startId,
            endSessionId: pair.endId,
            startWeek: clickedWeek,
        });
    },
    eventClick(info) {
        openEditModal(info.event.extendedProps.courseId);
    },
});
window.calendar = calendar;
window.openCreateModal = openCreateModal;
window.closeModal = closeModal;
window.saveEvent = saveEvent;
window.deleteCourse = deleteCourse;
window.addScheduleRow = addScheduleRow;
window.removeScheduleRow = removeScheduleRow;

calendar.render();

// ── Expose functions to global scope ───────────────────
window.handleCourseCardClick = handleCourseCardClick;
window.handleEditClick = handleEditClick;

// ── Initialize ─────────────────────────────────────────
loadTeacherCourses();
