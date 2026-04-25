<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendar</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cream: #f7f4ef;
            --paper: #fdfcf9;
            --ink: #1a1714;
            --ink-light: #6b6560;
            --ink-faint: #c4bfb8;
            --accent: #c0392b;
            --accent-soft: #f5ede9;
            --green: #2e7d56;
            --blue: #1a5276;
            --amber: #a0522d;
            --purple: #6c3483;
            --border: #e8e3db;
            --shadow: 0 2px 16px rgba(26, 23, 20, 0.08);
            --shadow-lg: 0 8px 40px rgba(26, 23, 20, 0.14);
        }

        body {
            background: var(--cream);
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── Layout ── */
        .app {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }

        .app-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .app-title {
            font-family: 'Lora', serif;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
            color: var(--ink);
        }

        .app-title span {
            color: var(--accent);
        }

        .btn-add {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--ink);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-add:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .btn-add:active {
            transform: translateY(0);
        }

        /* ── Calendar wrapper ── */
        .calendar-card {
            background: var(--paper);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 24px;
        }

        /* ── FullCalendar overrides ── */
        .fc {
            font-family: 'DM Sans', sans-serif;
        }

        .fc .fc-toolbar-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--ink);
        }

        .fc .fc-button {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            padding: 5px 12px;
            box-shadow: none;
            transition: background 0.12s, border-color 0.12s;
        }

        .fc .fc-button:hover {
            background: var(--cream);
            border-color: var(--ink-faint);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
            box-shadow: none;
        }

        .fc .fc-button:focus {
            box-shadow: none;
            outline: none;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: var(--border);
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-col-header-cell-cushion {
            color: var(--ink-light);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
        }

        .fc .fc-day-today {
            background: var(--accent-soft) !important;
        }

        .fc .fc-day-today .fc-daygrid-day-number {
            color: var(--accent);
            font-weight: 700;
        }

        /* Event chips */
        .fc .fc-event {
            border: none;
            border-radius: 5px;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
            transition: opacity 0.12s, transform 0.12s;
        }

        .fc .fc-event:hover {
            opacity: 0.88;
            transform: scale(1.02);
        }

        .fc .fc-event-title {
            font-weight: 500;
        }

        /* ── Modal overlay ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26, 23, 20, 0.45);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.15s ease;
        }

        .modal-overlay.open {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal {
            background: var(--paper);
            border-radius: 16px;
            width: 440px;
            max-width: calc(100vw - 40px);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: slideUp 0.2s cubic-bezier(0.34, 1.2, 0.64, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            padding: 22px 24px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--ink);
        }

        .modal-close {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-light);
            border-radius: 6px;
            font-size: 20px;
            line-height: 1;
            transition: background 0.12s, color 0.12s;
        }

        .modal-close:hover {
            background: var(--cream);
            color: var(--ink);
        }

        .modal-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Form fields */
        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 12px;
            font-weight: 500;
            color: var(--ink-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field input[type="text"],
        .field input[type="datetime-local"],
        .field input[type="date"],
        .field select {
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--ink);
            background: var(--cream);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--ink);
            box-shadow: 0 0 0 3px rgba(26, 23, 20, 0.07);
            background: var(--paper);
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* All-day toggle */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
        }

        .toggle-label {
            font-size: 14px;
            font-weight: 400;
            color: var(--ink);
        }

        .toggle {
            position: relative;
            width: 40px;
            height: 22px;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-track {
            position: absolute;
            inset: 0;
            background: var(--ink-faint);
            border-radius: 11px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .toggle-track::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
        }

        .toggle input:checked+.toggle-track {
            background: var(--ink);
        }

        .toggle input:checked+.toggle-track::before {
            transform: translateX(18px);
        }

        /* Color picker */
        .color-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .color-swatch {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: transform 0.1s, border-color 0.1s;
        }

        .color-swatch:hover {
            transform: scale(1.15);
        }

        .color-swatch.selected {
            border-color: var(--ink);
            transform: scale(1.15);
        }

        /* Modal footer */
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 9px 18px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.12s, transform 0.1s;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-cancel {
            background: var(--cream);
            color: var(--ink-light);
            border-color: var(--border);
        }

        .btn-cancel:hover {
            background: #ece7df;
        }

        .btn-delete {
            background: #fef1f0;
            color: #c0392b;
            border-color: #f5c6c2;
            margin-right: auto;
        }

        .btn-delete:hover {
            background: #fde0dc;
        }

        .btn-save {
            background: var(--ink);
            color: #fff;
        }

        .btn-save:hover {
            background: #333;
        }

        /* Hint bar */
        .hint-bar {
            margin-top: 16px;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hint {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--ink-light);
        }

        .hint-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--ink-faint);
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="app-header">
            <div class="app-title">My <span>Calendar</span></div>
            <button class="btn-add" onclick="openCreateModal()">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                New Event
            </button>
        </div>

        <div class="calendar-card">
            <div id="calendar"></div>
            <div class="hint-bar">
                <div class="hint">
                    <div class="hint-dot"></div> Click event to edit
                </div>
                <div class="hint">
                    <div class="hint-dot"></div> Click a time slot to create
                </div>
            </div>
        </div>
    </div>

    <!-- ── Modal ── -->
    <div class="modal-overlay" id="eventModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">New Course</h2>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>

            <div class="modal-body">
                <!-- Loading/error state -->
                <div id="modalStatus"
                    style="display:none; padding:8px 12px; border-radius:8px; font-size:13px; margin-bottom:4px;"></div>

                <div class="field">
                    <label>Course Title</label>
                    <input type="text" id="evTitle" placeholder="e.g. Mathematics 101…" />
                </div>

                <div class="field">
                    <label>Day of Week</label>
                    <select id="evDay">
                        <option value="0">Sunday</option>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                    </select>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Start Time</label>
                        <input type="time" id="evStart" step="300" />
                    </div>
                    <div class="field">
                        <label>End Time</label>
                        <input type="time" id="evEnd" step="300" />
                    </div>
                </div>

                <div class="field">
                    <label>Color</label>
                    <div class="color-options" id="colorOptions"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-delete" id="btnDelete" onclick="deleteEvent()"
                    style="display:none">Delete</button>
                <button class="btn btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn btn-save" id="btnSave" onclick="saveEvent()">Save</button>
            </div>
        </div>
    </div>

    <script>
        // ─────────────────────────────────────────
        // CONFIG — adjust these two values
        // ─────────────────────────────────────────
        const API_BASE = 'https://course-selection.test/api';
        const ADMIN_ID = @json(auth('admin')->id());

        // ─────────────────────────────────────────
        // Time helpers
        // startTime / endTime in DB = minutes from midnight
        // e.g. 480 = 08:00, 570 = 09:30, 1320 = 22:00
        // ─────────────────────────────────────────
        const pad = n => String(n).padStart(2, '0');

        function minutesToTimeStr(mins) {
            // Returns "HH:MM" for FullCalendar / <input type="time">
            return `${pad(Math.floor(mins / 60))}:${pad(mins % 60)}`;
        }

        function timeStrToMinutes(str) {
            // "HH:MM" → integer minutes from midnight
            const [h, m] = str.split(':').map(Number);
            return h * 60 + m;
        }

        // ─────────────────────────────────────────
        // Color palette
        // ─────────────────────────────────────────
        const COLORS = [{
                label: 'Rose',
                value: '#c0392b'
            },
            {
                label: 'Sienna',
                value: '#a0522d'
            },
            {
                label: 'Amber',
                value: '#d4a017'
            },
            {
                label: 'Forest',
                value: '#2e7d56'
            },
            {
                label: 'Teal',
                value: '#0e7490'
            },
            {
                label: 'Navy',
                value: '#1a5276'
            },
            {
                label: 'Plum',
                value: '#6c3483'
            },
            {
                label: 'Slate',
                value: '#455a64'
            },
        ];

        let selectedColor = COLORS[4].value;
        let editingEvent = null; // FullCalendar Event object currently being edited

        function hexToRgb(hex) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgb(${r}, ${g}, ${b})`;
        }

        // Build swatches once
        const colorOptionsEl = document.getElementById('colorOptions');
        COLORS.forEach(c => {
            const sw = document.createElement('div');
            sw.className = 'color-swatch' + (c.value === selectedColor ? ' selected' : '');
            sw.style.background = c.value;
            sw.title = c.label;
            sw.onclick = () => {
                document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
                sw.classList.add('selected');
                selectedColor = c.value;
            };
            colorOptionsEl.appendChild(sw);
        });

        function setSwatchColor(color) {
            selectedColor = color || COLORS[4].value;
            document.querySelectorAll('.color-swatch').forEach(sw => {
                sw.classList.toggle('selected',
                    sw.style.background === hexToRgb(selectedColor) ||
                    sw.style.background === selectedColor
                );
            });
        }

        // ─────────────────────────────────────────
        // Modal helpers
        // ─────────────────────────────────────────
        function showStatus(msg, type = 'error') {
            const el = document.getElementById('modalStatus');
            el.textContent = msg;
            el.style.display = 'block';
            el.style.background = type === 'error' ? '#fef1f0' : '#edfaf3';
            el.style.color = type === 'error' ? '#c0392b' : '#2e7d56';
            el.style.border = type === 'error' ? '1px solid #f5c6c2' : '1px solid #a8dfc0';
        }

        function hideStatus() {
            document.getElementById('modalStatus').style.display = 'none';
        }

        function setBusy(busy) {
            const btn = document.getElementById('btnSave');
            btn.disabled = busy;
            btn.textContent = busy ? 'Saving…' : 'Save';
        }

        function openCreateModal(dateInfo) {
            editingEvent = null;
            hideStatus();

            document.getElementById('modalTitle').textContent = 'New Course';
            document.getElementById('evTitle').value = '';
            document.getElementById('btnDelete').style.display = 'none';

            // Pre-fill day + time from the clicked slot, if provided
            if (dateInfo) {
                document.getElementById('evDay').value = dateInfo.day;
                document.getElementById('evStart').value = dateInfo.startTime || '09:00';
                document.getElementById('evEnd').value = dateInfo.endTime || '10:00';
            } else {
                document.getElementById('evDay').value = new Date().getDay();
                document.getElementById('evStart').value = '09:00';
                document.getElementById('evEnd').value = '10:00';
            }

            setSwatchColor(COLORS[4].value);
            document.getElementById('eventModal').classList.add('open');
            setTimeout(() => document.getElementById('evTitle').focus(), 50);
        }

        function openEditModal(event) {
            editingEvent = event;
            hideStatus();

            document.getElementById('modalTitle').textContent = 'Edit Course';
            document.getElementById('evTitle').value = event.title;
            document.getElementById('btnDelete').style.display = '';

            // daysOfWeek is an array on recurring events — grab the first entry
            const dow = event.extendedProps.day ?? (event.start ? event.start.getDay() : 1);
            document.getElementById('evDay').value = dow;
            document.getElementById('evStart').value = minutesToTimeStr(event.extendedProps.startTime);
            document.getElementById('evEnd').value = minutesToTimeStr(event.extendedProps.endTime);

            setSwatchColor(event.backgroundColor);
            document.getElementById('eventModal').classList.add('open');
            setTimeout(() => document.getElementById('evTitle').focus(), 50);
        }

        function closeModal() {
            document.getElementById('eventModal').classList.remove('open');
            editingEvent = null;
        }

        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ─────────────────────────────────────────
        // Validate form
        // ─────────────────────────────────────────
        function validate() {
            const title = document.getElementById('evTitle').value.trim();
            const start = document.getElementById('evStart').value;
            const end = document.getElementById('evEnd').value;

            if (!title) {
                document.getElementById('evTitle').focus();
                showStatus('Please enter a course title.');
                return false;
            }
            if (!start || !end) {
                showStatus('Please fill in both start and end times.');
                return false;
            }
            if (timeStrToMinutes(start) >= timeStrToMinutes(end)) {
                showStatus('End time must be after start time.');
                return false;
            }
            return true;
        }

        // ─────────────────────────────────────────
        // API calls
        // ─────────────────────────────────────────
        async function apiFetch(path, options = {}) {
            const res = await fetch(`${API_BASE}${path}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // If you use Laravel Sanctum / session auth, add:
                    // 'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                },
                ...options,
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message || `HTTP ${res.status}`);
            }
            return res.status === 204 ? null : res.json();
        }

        // ─────────────────────────────────────────
        // Map API course → FullCalendar event
        // ─────────────────────────────────────────
        function courseToFcEvent(course) {
            return {
                id: String(course.id),
                title: course.title,
                daysOfWeek: [course.day], // recurring on this weekday
                startTime: minutesToTimeStr(course.startTime),
                endTime: minutesToTimeStr(course.endTime),
                backgroundColor: course.color || COLORS[4].value,
                borderColor: course.color || COLORS[4].value,
                textColor: '#ffffff',
                extendedProps: {
                    // Keep raw DB values for the edit modal
                    day: course.day,
                    startTime: course.startTime,
                    endTime: course.endTime,
                },
            };
        }

        // ─────────────────────────────────────────
        // Save (create or update)
        // ─────────────────────────────────────────
        async function saveEvent() {
            if (!validate()) return;

            const payload = {
                title: document.getElementById('evTitle').value.trim(),
                day: parseInt(document.getElementById('evDay').value, 10),
                startTime: timeStrToMinutes(document.getElementById('evStart').value),
                endTime: timeStrToMinutes(document.getElementById('evEnd').value),
                color: selectedColor,
                admin_id: ADMIN_ID,
            };

            setBusy(true);
            hideStatus();

            try {
                if (editingEvent) {
                    // ── UPDATE ──
                    const updated = await apiFetch(`/course/${editingEvent.id}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload),
                    });

                    // Patch FullCalendar event in place (no full re-fetch needed)
                    editingEvent.setProp('title', updated.title);
                    editingEvent.setProp('backgroundColor', updated.color);
                    editingEvent.setProp('borderColor', updated.color);
                    editingEvent.setExtendedProp('day', updated.day);
                    editingEvent.setExtendedProp('startTime', updated.startTime);
                    editingEvent.setExtendedProp('endTime', updated.endTime);
                    // Recurring time change requires remove + re-add
                    editingEvent.remove();
                    calendar.addEvent(courseToFcEvent(updated));

                } else {
                    // ── CREATE ──
                    const created = await apiFetch('/course/store', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });
                    calendar.addEvent(courseToFcEvent(created));
                }

                closeModal();
            } catch (err) {
                showStatus(err.message || 'Something went wrong. Please try again.');
            } finally {
                setBusy(false);
            }
        }

        // ─────────────────────────────────────────
        // Delete
        // ─────────────────────────────────────────
        async function deleteEvent() {
            if (!editingEvent) return;
            if (!confirm(`Delete "${editingEvent.title}"?`)) return;

            setBusy(true);
            try {
                await apiFetch(`/course/${editingEvent.id}`, {
                    method: 'DELETE'
                });
                editingEvent.remove();
                closeModal();
            } catch (err) {
                showStatus(err.message || 'Could not delete. Please try again.');
            } finally {
                setBusy(false);
            }
        }

        // ── Keyboard shortcuts ──
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') saveEvent();
        });

        // ─────────────────────────────────────────
        // FullCalendar init
        // ─────────────────────────────────────────
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '22:00:00',
            scrollTime: '08:00:00',
            height: 'auto',
            editable: false, // Recurring events can't be meaningfully dragged
            selectable: true,
            selectMirror: true,
            nowIndicator: true,

            // ── Load courses from API ──
            events(fetchInfo, successCallback, failureCallback) {
                apiFetch(`/course?admin_id=${ADMIN_ID}`)
                    .then(courses => successCallback(courses.map(courseToFcEvent)))
                    .catch(err => {
                        console.error('Failed to load courses:', err);
                        failureCallback(err);
                    });
            },

            // Click empty time slot → prefill day + time in modal
            dateClick(info) {
                const d = info.date;
                const end = new Date(d.getTime() + 60 * 60 * 1000); // +1 hour default
                openCreateModal({
                    day: d.getDay(),
                    startTime: `${pad(d.getHours())}:${pad(d.getMinutes())}`,
                    endTime: `${pad(end.getHours())}:${pad(end.getMinutes())}`,
                });
            },

            // Drag-select a range → prefill
            select(info) {
                openCreateModal({
                    day: info.start.getDay(),
                    startTime: `${pad(info.start.getHours())}:${pad(info.start.getMinutes())}`,
                    endTime: `${pad(info.end.getHours())}:${pad(info.end.getMinutes())}`,
                });
            },

            // Click existing event → edit
            eventClick(info) {
                openEditModal(info.event);
            },
        });

        calendar.render();
    </script>
</body>

</html>
