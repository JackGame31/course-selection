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
const PALETTE = [
    "#1a5276",
    "#6c3483",
    "#2e7d56",
    "#a0522d",
    "#0e7490",
    "#c0392b",
    "#455a64",
    "#7d6608",
    "#117a65",
    "#784212",
];
function seededColor(courseId) {
    let seed = (ADMIN_ID * 2654435761 + courseId * 1664525 + 1013904223) >>> 0;
    seed ^= seed >>> 16;
    seed = Math.imul(seed, 0x45d9f3b) >>> 0;
    seed ^= seed >>> 16;
    seed = Math.imul(seed, 0x45d9f3b) >>> 0;
    seed ^= seed >>> 16;
    return PALETTE[seed % PALETTE.length];
}
// ── App state ─────────────────────────────────────────
const ADMIN_ID = document.querySelector('meta[name="admin-id"]').content;
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const courseStore = new Map();
let editingCourseId = null;
let scheduleRowCount = 0;
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
// ── Course → FullCalendar events ──────────────────────
function courseColor(id) {
    return seededColor(id);
}
function courseToFcEvents(course) {
    const color = courseColor(course.id);
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
// ── Schedule row UI ───────────────────────────────────
function buildDayOptions(selected) {
    return DAYS.map((d, i) => {
        const val = i + 1;
        return `<option value="${val}"${val === selected ? " selected" : ""}>${d}</option>`;
    }).join("");
}
function buildPairOptions(selStart, selEnd) {
    return SESSION_PAIRS.map(
        (p) =>
            `<option value="${p.startId}:${p.endId}"${p.startId === selStart && p.endId === selEnd ? " selected" : ""}>${p.label}</option>`,
    ).join("");
}
// Shared input classes for dynamically built schedule rows
const inputCls =
    "field-input px-3 py-2 border border-[#e8e3db] rounded-lg text-sm text-[#1a1714] bg-[#f7f4ef] w-full transition-colors";
const labelCls =
    "text-[11px] font-medium text-[#6b6560] uppercase tracking-wide";
function addScheduleRow(schedule = null) {
    const rows = document.getElementById("scheduleRows");
    if (rows.children.length >= 2) return;
    const idx = scheduleRowCount++;
    const dayVal = schedule ? schedule.day_of_week : idx + 1;
    const endWeekVal = schedule ? schedule.end_week : 19;
    const startSId = schedule ? schedule.start_session_id : 1;
    const endSId = schedule ? schedule.end_session_id : 2;
    const div = document.createElement("div");
    div.className =
        "border border-[#e8e3db] rounded-xl p-4 flex flex-col gap-3 bg-[#f7f4ef]";
    if (schedule?.id) div.dataset.scheduleId = schedule.id;
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="schedule-card-label text-[11px] font-semibold text-[#6b6560] uppercase tracking-wide">
                Class Time ${rows.children.length + 1}
            </span>
            <button onclick="removeScheduleRow(this)" title="Remove"
                class="text-[#6b6560] text-lg leading-none px-0.5 rounded hover:text-[#c0392b] hover:bg-[#f5ede9] transition-colors">
                &#x2715;
            </button>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1.5">
                <label class="${labelCls}">Day of Week</label>
                <select class="sched-day ${inputCls}">${buildDayOptions(dayVal)}</select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="${labelCls}">End Week</label>
                <input type="number" class="sched-endweek ${inputCls}" min="1" max="19" value="${endWeekVal}" />
                <span class="sched-endweek-hint text-[11px] text-[#6b6560] min-h-[14px]"></span>
            </div>
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="${labelCls}">Session Block</label>
            <select class="sched-pair ${inputCls}">${buildPairOptions(startSId, endSId)}</select>
        </div>
    `;
    rows.appendChild(div);
    refreshEndWeekHint(div);
    renumberScheduleLabels();
    updateAddButton();
}
function removeScheduleRow(btn) {
    if (document.getElementById("scheduleRows").children.length <= 1) return;
    btn.closest(".border.border-\\[\\#e8e3db\\]") ||
        btn.closest("[data-schedule-id]") ||
        btn.parentElement.parentElement.parentElement.remove();
    // Simpler: traverse up to the schedule card div
    let el = btn;
    while (el && !el.classList.contains("rounded-xl")) el = el.parentElement;
    if (el) el.remove();
    renumberScheduleLabels();
    updateAddButton();
}
function renumberScheduleLabels() {
    document
        .querySelectorAll("#scheduleRows .schedule-card-label")
        .forEach((label, i) => {
            label.textContent = `Class Time ${i + 1}`;
        });
}
function updateAddButton() {
    const count = document.getElementById("scheduleRows").children.length;
    document.getElementById("btnAddSchedule").style.display =
        count >= 2 ? "none" : "";
}
function refreshEndWeekHint(container) {
    container.querySelectorAll(".sched-endweek").forEach((input) => {
        const hint = input.nextElementSibling;
        const v = parseInt(input.value);
        if (hint) hint.textContent = v >= 1 && v <= 19 ? weekLabel(v) : "";
    });
}
document.addEventListener("input", (e) => {
    if (e.target.id === "evStartWeek") {
        const v = parseInt(e.target.value);
        document.getElementById("startWeekHint").textContent =
            v >= 1 && v <= 19 ? weekLabel(v) : "";
    }
    if (e.target.classList.contains("sched-endweek")) {
        refreshEndWeekHint(
            e.target.closest(".rounded-xl") ||
                e.target.parentElement.parentElement,
        );
    }
});
// ── Modal open/close ──────────────────────────────────
function resetModal() {
    document.getElementById("scheduleRows").innerHTML = "";
    scheduleRowCount = 0;
    hideStatus();
}
function openCreateModal(prefill = null) {
    editingCourseId = null;
    resetModal();
    document.getElementById("modalTitle").textContent = "New Course";
    document.getElementById("evTitle").value = "";
    document.getElementById("btnDelete").style.display = "none";
    const defaultStartWeek = prefill?.startWeek || 1;
    document.getElementById("evStartWeek").value = defaultStartWeek;
    document.getElementById("startWeekHint").textContent =
        weekLabel(defaultStartWeek);
    const schedPrefill = prefill
        ? {
              day_of_week: prefill.dayOfWeek,
              start_session_id: prefill.startSessionId,
              end_session_id: prefill.endSessionId,
              end_week: 19,
          }
        : null;
    addScheduleRow(schedPrefill);
    document.getElementById("eventModal").classList.add("open");
    setTimeout(() => document.getElementById("evTitle").focus(), 50);
}
function openEditModal(courseId) {
    const course = courseStore.get(courseId);
    if (!course) return;
    editingCourseId = courseId;
    resetModal();
    document.getElementById("modalTitle").textContent = "Edit Course";
    document.getElementById("evTitle").value = course.title;
    document.getElementById("btnDelete").style.display = "";
    const startWeek = course.schedules?.[0]?.start_week ?? 1;
    document.getElementById("evStartWeek").value = startWeek;
    document.getElementById("startWeekHint").textContent = weekLabel(startWeek);
    (course.schedules || []).forEach((s) => addScheduleRow(s));
    document.getElementById("eventModal").classList.add("open");
    setTimeout(() => document.getElementById("evTitle").focus(), 50);
}
function closeModal() {
    document.getElementById("eventModal").classList.remove("open");
    editingCourseId = null;
}
document.getElementById("eventModal").addEventListener("click", function (e) {
    if (e.target === this) closeModal();
});
// ── Status / busy ─────────────────────────────────────
function showStatus(msg, type = "error") {
    const el = document.getElementById("modalStatus");
    el.textContent = msg;
    el.classList.remove("hidden");
    if (type === "error") {
        el.style.background = "#fef1f0";
        el.style.color = "#c0392b";
        el.style.border = "1px solid #f5c6c2";
    } else {
        el.style.background = "#edfaf3";
        el.style.color = "#2e7d56";
        el.style.border = "1px solid #a8dfc0";
    }
}
function hideStatus() {
    const el = document.getElementById("modalStatus");
    el.classList.add("hidden");
    el.textContent = "";
}
function setBusy(busy) {
    const btn = document.getElementById("btnSave");
    btn.disabled = busy;
    btn.textContent = busy ? "Saving..." : "Save";
}
// ── Validation & form data ────────────────────────────
function collectSchedules() {
    return Array.from(
        document.querySelectorAll("#scheduleRows .sched-pair"),
    ).map((select) => {
        const card =
            select.closest(".rounded-xl") ||
            select.parentElement.parentElement.parentElement;
        const [startId, endId] = select.value.split(":").map(Number);
        return {
            id: card.dataset.scheduleId
                ? parseInt(card.dataset.scheduleId)
                : undefined,
            day_of_week: parseInt(card.querySelector(".sched-day").value),
            start_session_id: startId,
            end_session_id: endId,
            end_week: parseInt(card.querySelector(".sched-endweek").value),
        };
    });
}
function validate() {
    const title = document.getElementById("evTitle").value.trim();
    const startWeek = parseInt(document.getElementById("evStartWeek").value);
    if (!title) {
        showStatus("Please enter a course title.");
        return false;
    }
    if (startWeek < 1 || startWeek > 19) {
        showStatus("Start week must be 1–19.");
        return false;
    }
    const schedules = collectSchedules();
    if (!schedules.length) {
        showStatus("At least one class time is required.");
        return false;
    }
    for (const s of schedules) {
        if (s.end_week < startWeek || s.end_week > 19) {
            showStatus(`End week must be between ${startWeek} and 19.`);
            return false;
        }
    }
    const days = schedules.map((s) => s.day_of_week);
    if (new Set(days).size !== days.length) {
        showStatus("Each class time must be on a different day.");
        return false;
    }
    return true;
}
// ── Save ──────────────────────────────────────────────
async function saveEvent() {
    if (!validate()) return;
    const startWeek = parseInt(document.getElementById("evStartWeek").value);
    const payload = {
        title: document.getElementById("evTitle").value.trim(),
        admin_id: ADMIN_ID,
        schedules: collectSchedules().map((s) => ({
            ...s,
            start_week: startWeek,
        })),
    };
    setBusy(true);
    hideStatus();
    try {
        let saved;
        if (editingCourseId) {
            saved = await apiFetch(`/course/${editingCourseId}`, {
                method: "PUT",
                body: JSON.stringify(payload),
            });
            removeCourseEvents(editingCourseId);
            courseStore.delete(editingCourseId);
        } else {
            saved = await apiFetch("/course/store", {
                method: "POST",
                body: JSON.stringify(payload),
            });
        }
        addCourseToCalendar(saved);
        closeModal();
    } catch (err) {
        showStatus(err.message || "Something went wrong. Please try again.");
    } finally {
        setBusy(false);
    }
}
// ── Delete ────────────────────────────────────────────
async function deleteCourse() {
    if (!editingCourseId) return;
    const course = courseStore.get(editingCourseId);
    if (!confirm(`Delete "${course?.title}"? This cannot be undone.`)) return;
    setBusy(true);
    try {
        await apiFetch(`/course/${editingCourseId}`, {
            method: "DELETE",
        });
        removeCourseEvents(editingCourseId);
        courseStore.delete(editingCourseId);
        closeModal();
    } catch (err) {
        showStatus(err.message || "Could not delete. Please try again.");
    } finally {
        setBusy(false);
    }
}
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeModal();
    if ((e.metaKey || e.ctrlKey) && e.key === "Enter") saveEvent();
});
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
window.openCreateModal = openCreateModal;
window.closeModal = closeModal;
window.saveEvent = saveEvent;
window.deleteCourse = deleteCourse;
window.addScheduleRow = addScheduleRow;
window.removeScheduleRow = removeScheduleRow;
calendar.render();
