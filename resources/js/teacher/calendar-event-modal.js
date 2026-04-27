// ── Schedule row UI ───────────────────────────────────
export function buildDayOptions(selected) {
    return DAYS.map((d, i) => {
        const val = i + 1;
        return `<option value="${val}"${val === selected ? " selected" : ""}>${d}</option>`;
    }).join("");
}

export function buildPairOptions(selStart, selEnd) {
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

export function addScheduleRow(schedule = null) {
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

export function removeScheduleRow(btn) {
    if (document.getElementById("scheduleRows").children.length <= 1) return;
    // Simpler: traverse up to the schedule card div
    let el = btn;
    while (el && !el.classList.contains("rounded-xl")) el = el.parentElement;
    if (el) el.remove();
    renumberScheduleLabels();
    updateAddButton();
}

export function renumberScheduleLabels() {
    document
        .querySelectorAll("#scheduleRows .schedule-card-label")
        .forEach((label, i) => {
            label.textContent = `Class Time ${i + 1}`;
        });
}

export function updateAddButton() {
    const count = document.getElementById("scheduleRows").children.length;
    document.getElementById("btnAddSchedule").style.display =
        count >= 2 ? "none" : "";
}

export function refreshEndWeekHint(container) {
    container.querySelectorAll(".sched-endweek").forEach((input) => {
        const hint = input.nextElementSibling;
        const v = parseInt(input.value);
        if (hint) hint.textContent = v >= 1 && v <= 19 ? weekLabel(v) : "";
    });
}

// ── Modal open/close ──────────────────────────────────
export function resetModal() {
    document.getElementById("scheduleRows").innerHTML = "";
    scheduleRowCount = 0;
    hideStatus();
}

export function openCreateModal(prefill = null) {
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

export function openEditModal(courseId) {
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

export function closeModal() {
    document.getElementById("eventModal").classList.remove("open");
    editingCourseId = null;
}

// ── Status / busy ─────────────────────────────────────
export function showStatus(msg, type = "error") {
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

export function hideStatus() {
    const el = document.getElementById("modalStatus");
    el.classList.add("hidden");
    el.textContent = "";
}

export function setBusy(busy) {
    const btn = document.getElementById("btnSave");
    btn.disabled = busy;
    btn.textContent = busy ? "Saving..." : "Save";
}

// ── Validation & form data ────────────────────────────
export function collectSchedules() {
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

export function validate() {
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
export async function saveEvent() {
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
        renderCourseList();
        updateCourseCount();
    } catch (err) {
        showStatus(err.message || "Something went wrong. Please try again.");
    } finally {
        setBusy(false);
    }
}

// ── Delete ────────────────────────────────────────────
export async function deleteCourse() {
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
        renderCourseList();
        updateCourseCount();
    } catch (err) {
        showStatus(err.message || "Could not delete. Please try again.");
    } finally {
        setBusy(false);
    }
}

// Event listeners
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

document.getElementById("eventModal").addEventListener("click", function (e) {
    if (e.target === this) closeModal();
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeModal();
    if ((e.metaKey || e.ctrlKey) && e.key === "Enter") saveEvent();
});
