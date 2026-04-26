<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        }

        .app-title span {
            color: var(--accent);
        }

        .app-meta {
            font-size: 12px;
            color: var(--ink-light);
            margin-top: 4px;
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

        .calendar-card {
            background: var(--paper);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 24px;
        }

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
            transition: background 0.12s;
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

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26, 23, 20, 0.45);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: var(--paper);
            border-radius: 16px;
            width: 500px;
            max-width: calc(100vw - 40px);
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
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
            position: sticky;
            top: 0;
            background: var(--paper);
            z-index: 1;
        }

        .modal-header h2 {
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 600;
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
        .field input[type="number"],
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
            width: 100%;
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

        .week-hint {
            font-size: 11px;
            color: var(--ink-light);
            margin-top: 2px;
            min-height: 14px;
        }

        .schedule-card {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: var(--cream);
        }

        .schedule-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .schedule-card-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-remove-schedule {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-light);
            font-size: 18px;
            line-height: 1;
            padding: 0 2px;
            border-radius: 4px;
            transition: color 0.12s, background 0.12s;
        }

        .btn-remove-schedule:hover {
            color: var(--accent);
            background: var(--accent-soft);
        }

        .btn-add-schedule {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            padding: 9px;
            border: 1px dashed var(--ink-faint);
            border-radius: 8px;
            background: none;
            color: var(--ink-light);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            cursor: pointer;
            transition: border-color 0.12s, color 0.12s, background 0.12s;
        }

        .btn-add-schedule:hover {
            border-color: var(--ink);
            color: var(--ink);
            background: var(--cream);
        }

        #modalStatus {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            display: none;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 0 -24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            position: sticky;
            bottom: 0;
            background: var(--paper);
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

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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

        /* Add this to your <style> section */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background: var(--paper);
            color: var(--ink-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-logout:hover {
            background: #fef1f0;
            color: #c0392b;
            border-color: #f5c6c2;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="app-header">
            <div>
                <div class="app-title">My <span>Calendar</span></div>
                <div class="app-meta">Semester: Week 1 (2 Mar) &rarr; Week 19 (12 Jul)</div>
            </div>

            <div class="header-actions">
                <button class="btn-add" onclick="openCreateModal()">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    New Course
                </button>

                <form method="POST" action="{{ route('teacher.logout') }}" style="margin: 0;">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn-logout">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="calendar-card">
            <div id="calendar"></div>
            <div class="hint-bar">
                <div class="hint">
                    <div class="hint-dot"></div> Click event to edit course
                </div>
                <div class="hint">
                    <div class="hint-dot"></div> Click a time slot to create
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="eventModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">New Course</h2>
                <button class="modal-close" onclick="closeModal()">&#x2715;</button>
            </div>
            <div class="modal-body">
                <div id="modalStatus"></div>

                <div class="field">
                    <label>Course Title</label>
                    <input type="text" id="evTitle" placeholder="e.g. Calculus I (A)" />
                </div>

                <div class="field" style="max-width:160px;">
                    <label>Start Week</label>
                    <input type="number" id="evStartWeek" min="1" max="19" value="1" />
                    <span class="week-hint" id="startWeekHint"></span>
                </div>

                <div class="divider"></div>

                <div id="scheduleRows" style="display:flex;flex-direction:column;gap:12px;"></div>

                <button class="btn-add-schedule" id="btnAddSchedule" onclick="addScheduleRow()">
                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                        <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    Add 2nd Class Time
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-delete" id="btnDelete" onclick="deleteCourse()" style="display:none">Delete
                    Course</button>
                <button class="btn btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn btn-save" id="btnSave" onclick="saveEvent()">Save</button>
            </div>
        </div>
    </div>

    <script>
        // ── Static academic data ──────────────────────────────

        // Week 1 = Monday 2 March 2026; day_of_week: 1=Mon…5=Fri
        const SEMESTER_START = new Date('2026-03-02T00:00:00');
        const SEMESTER_END = new Date('2026-07-12T23:59:59');

        // 11 predefined sessions (seeded, never changes)
        const SESSIONS = {
            1: {
                start: '08:00',
                end: '08:50'
            },
            2: {
                start: '08:55',
                end: '09:45'
            },
            3: {
                start: '10:15',
                end: '11:05'
            },
            4: {
                start: '11:10',
                end: '12:00'
            },
            5: {
                start: '14:00',
                end: '14:50'
            },
            6: {
                start: '14:55',
                end: '15:45'
            },
            7: {
                start: '16:15',
                end: '17:05'
            },
            8: {
                start: '17:10',
                end: '18:00'
            },
            9: {
                start: '18:45',
                end: '19:35'
            },
            10: {
                start: '19:40',
                end: '20:30'
            },
            11: {
                start: '20:30',
                end: '21:20'
            },
        };

        // Only valid session blocks per university rules
        const SESSION_PAIRS = [{
                label: 'Sessions 1-2  (08:00 - 09:45)',
                startId: 1,
                endId: 2
            },
            {
                label: 'Sessions 3-4  (10:15 - 12:00)',
                startId: 3,
                endId: 4
            },
            {
                label: 'Sessions 5-6  (14:00 - 15:45)',
                startId: 5,
                endId: 6
            },
            {
                label: 'Sessions 7-8  (16:15 - 18:00)',
                startId: 7,
                endId: 8
            },
            {
                label: 'Sessions 9-10 (18:45 - 20:30)',
                startId: 9,
                endId: 10
            },
            {
                label: 'Sessions 9-11 (18:45 - 21:20)',
                startId: 9,
                endId: 11
            },
        ];

        const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Color palette — no color in DB, generated client-side with seeded PRNG
        const PALETTE = ['#1a5276', '#6c3483', '#2e7d56', '#a0522d', '#0e7490', '#c0392b', '#455a64', '#7d6608', '#117a65',
            '#784212'
        ];

        // Seeded PRNG (mulberry32). Seed = mix of admin_id + course_id for stable, unique colors.
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
        const ADMIN_ID = @json(auth('admin')->id());
        const csrfToken = () => document.querySelector('meta[name="csrf-token"]').content;
        const courseStore = new Map(); // Map<id, course>
        let editingCourseId = null;
        let scheduleRowCount = 0;

        // Debug logging
        console.log('🔍 DEBUG: ADMIN_ID =', ADMIN_ID);
        console.log('🔍 DEBUG: CSRF Token =', csrfToken());

        // ── Date/week helpers ─────────────────────────────────
        const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        function weekToMonday(week) {
            const d = new Date(SEMESTER_START);
            d.setDate(d.getDate() + (week - 1) * 7);
            return d;
        }

        // DB day_of_week: 0=Sun,1=Mon…6=Sat (standard JS convention per schema)
        // SEMESTER_START is Monday (day 1), so offset from it = dayOfWeek - 1
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
            const normalized = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            if (normalized < SEMESTER_START || normalized > SEMESTER_END) return null;
            const diffDays = Math.floor((normalized - SEMESTER_START) / (1000 * 60 * 60 * 24));
            return Math.floor(diffDays / 7) + 1;
        }

        // DB day_of_week matches standard JS Date.getDay() values (0=Sun,1=Mon…6=Sat)
        function jsDateToDayOfWeek(date) {
            return date.getDay();
        }

        function guessSessionPair(date) {
            const mins = date.getHours() * 60 + date.getMinutes();
            let bestPair = SESSION_PAIRS[0];
            let bestDistance = Infinity;
            for (const pair of SESSION_PAIRS) {
                const [ph, pm] = SESSIONS[pair.startId].start.split(':').map(Number);
                const [eh, em] = SESSIONS[pair.endId].end.split(':').map(Number);
                const midpoint = Math.floor(((ph * 60 + pm) + (eh * 60 + em)) / 2);
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
            (course.schedules || []).forEach(sch => {
                const ss = SESSIONS[sch.start_session_id];
                const es = SESSIONS[sch.end_session_id];
                if (!ss || !es) return;
                const [sh, sm] = ss.start.split(':').map(Number);
                const [eh, em] = es.end.split(':').map(Number);
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
                        textColor: '#fff',
                        extendedProps: {
                            courseId: course.id
                        },
                    });
                }
            });
            return events;
        }

        // ── API helper ────────────────────────────────────────
        async function apiFetch(path, options = {}) {
            const method = (options.method || 'GET').toUpperCase();
            const headers = {
                'Accept': 'application/json'
            };
            if (method !== 'GET') {
                headers['Content-Type'] = 'application/json';
                headers['X-CSRF-TOKEN'] = csrfToken();
            }

            const fullUrl = `/api${path}`;
            console.log(`📡 API ${method}:`, fullUrl);

            const res = await fetch(fullUrl, {
                ...options,
                headers
            });

            console.log(`📡 Response Status:`, res.status, res.statusText);

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                console.error('❌ API Error:', err);
                throw new Error(err.message || `HTTP ${res.status}`);
            }

            const data = res.status === 204 ? null : await res.json();
            console.log(`✅ API Response:`, data);
            return data;
        }

        // ── Calendar store helpers ────────────────────────────
        function removeCourseEvents(courseId) {
            calendar.getEvents()
                .filter(e => e.extendedProps.courseId === courseId)
                .forEach(e => e.remove());
        }

        function addCourseToCalendar(course) {
            courseStore.set(course.id, course);
            courseToFcEvents(course).forEach(ev => calendar.addEvent(ev));
        }

        // ── Schedule row UI ───────────────────────────────────
        // day_of_week values: 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri
        function buildDayOptions(selected) {
            return DAYS.map((d, i) => {
                const val = i + 1; // 1-based (Mon=1…Fri=5)
                return `<option value="${val}"${val === selected ? ' selected' : ''}>${d}</option>`;
            }).join('');
        }

        function buildPairOptions(selStart, selEnd) {
            return SESSION_PAIRS.map(p =>
                `<option value="${p.startId}:${p.endId}"${p.startId === selStart && p.endId === selEnd ? ' selected' : ''}>${p.label}</option>`
            ).join('');
        }

        function addScheduleRow(schedule = null) {
            const rows = document.getElementById('scheduleRows');
            if (rows.children.length >= 2) return;

            const idx = scheduleRowCount++;
            const dayVal = schedule ? schedule.day_of_week : idx + 1; // 1=Mon for first, 2=Tue for second
            const endWeekVal = schedule ? schedule.end_week : 19;
            const startSId = schedule ? schedule.start_session_id : 1;
            const endSId = schedule ? schedule.end_session_id : 2;

            const div = document.createElement('div');
            div.className = 'schedule-card';
            if (schedule?.id) div.dataset.scheduleId = schedule.id;

            div.innerHTML = `
        <div class="schedule-card-header">
          <span class="schedule-card-label">Class Time ${rows.children.length + 1}</span>
          <button class="btn-remove-schedule" onclick="removeScheduleRow(this)" title="Remove">&#x2715;</button>
        </div>
        <div class="field-row">
          <div class="field">
            <label>Day of Week</label>
            <select class="sched-day">${buildDayOptions(dayVal)}</select>
          </div>
          <div class="field" style="max-width:160px;">
            <label>End Week</label>
            <input type="number" class="sched-endweek" min="1" max="19" value="${endWeekVal}" />
            <span class="week-hint sched-endweek-hint"></span>
          </div>
        </div>
        <div class="field">
          <label>Session Block</label>
          <select class="sched-pair">${buildPairOptions(startSId, endSId)}</select>
        </div>
      `;

            rows.appendChild(div);
            refreshEndWeekHint(div);
            renumberScheduleLabels();
            updateAddButton();
        }

        function removeScheduleRow(btn) {
            if (document.getElementById('scheduleRows').children.length <= 1) return;
            btn.closest('.schedule-card').remove();
            renumberScheduleLabels();
            updateAddButton();
        }

        function renumberScheduleLabels() {
            document.querySelectorAll('#scheduleRows .schedule-card').forEach((card, i) => {
                card.querySelector('.schedule-card-label').textContent = `Class Time ${i + 1}`;
            });
        }

        function updateAddButton() {
            const count = document.getElementById('scheduleRows').children.length;
            document.getElementById('btnAddSchedule').style.display = count >= 2 ? 'none' : '';
        }

        function refreshEndWeekHint(container) {
            container.querySelectorAll('.sched-endweek').forEach(input => {
                const hint = input.nextElementSibling;
                const v = parseInt(input.value);
                if (hint) hint.textContent = (v >= 1 && v <= 19) ? weekLabel(v) : '';
            });
        }

        // Live hints on input
        document.addEventListener('input', e => {
            if (e.target.id === 'evStartWeek') {
                const v = parseInt(e.target.value);
                document.getElementById('startWeekHint').textContent = (v >= 1 && v <= 19) ? weekLabel(v) : '';
            }
            if (e.target.classList.contains('sched-endweek')) {
                refreshEndWeekHint(e.target.closest('.schedule-card'));
            }
        });

        // ── Modal open/close ──────────────────────────────────
        function resetModal() {
            document.getElementById('scheduleRows').innerHTML = '';
            scheduleRowCount = 0;
            hideStatus();
        }

        function openCreateModal(prefill = null) {
            editingCourseId = null;
            resetModal();
            document.getElementById('modalTitle').textContent = 'New Course';
            document.getElementById('evTitle').value = '';
            document.getElementById('btnDelete').style.display = 'none';

            // Get the week from prefill, or default to 1
            const defaultStartWeek = prefill?.startWeek || 1;

            document.getElementById('evStartWeek').value = defaultStartWeek;
            document.getElementById('startWeekHint').textContent = weekLabel(defaultStartWeek);

            const schedPrefill = prefill ? {
                    day_of_week: prefill.dayOfWeek,
                    start_session_id: prefill.startSessionId,
                    end_session_id: prefill.endSessionId,
                    end_week: 19
                } :
                null;
            addScheduleRow(schedPrefill);

            document.getElementById('eventModal').classList.add('open');
            setTimeout(() => document.getElementById('evTitle').focus(), 50);
        }

        function openEditModal(courseId) {
            const course = courseStore.get(courseId);
            if (!course) return;
            editingCourseId = courseId;
            resetModal();
            document.getElementById('modalTitle').textContent = 'Edit Course';
            document.getElementById('evTitle').value = course.title;
            document.getElementById('btnDelete').style.display = '';
            const startWeek = course.schedules?.[0]?.start_week ?? 1;
            document.getElementById('evStartWeek').value = startWeek;
            document.getElementById('startWeekHint').textContent = weekLabel(startWeek);
            (course.schedules || []).forEach(s => addScheduleRow(s));
            document.getElementById('eventModal').classList.add('open');
            setTimeout(() => document.getElementById('evTitle').focus(), 50);
        }

        function closeModal() {
            document.getElementById('eventModal').classList.remove('open');
            editingCourseId = null;
        }

        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ── Status / busy ─────────────────────────────────────
        function showStatus(msg, type = 'error') {
            const el = document.getElementById('modalStatus');
            el.textContent = msg;
            el.style.display = 'block';
            el.style.background = type === 'error' ? '#fef1f0' : '#edfaf3';
            el.style.color = type === 'error' ? '#c0392b' : '#2e7d56';
            el.style.border = type === 'error' ? '1px solid #f5c6c2' : '1px solid #a8dfc0';
        }

        function hideStatus() {
            const el = document.getElementById('modalStatus');
            el.style.display = 'none';
            el.textContent = '';
        }

        function setBusy(busy) {
            const btn = document.getElementById('btnSave');
            btn.disabled = busy;
            btn.textContent = busy ? 'Saving...' : 'Save';
        }

        // ── Validation & form data ────────────────────────────
        function collectSchedules() {
            return Array.from(document.querySelectorAll('#scheduleRows .schedule-card')).map(card => {
                const [startId, endId] = card.querySelector('.sched-pair').value.split(':').map(Number);
                return {
                    id: card.dataset.scheduleId ? parseInt(card.dataset.scheduleId) : undefined,
                    day_of_week: parseInt(card.querySelector('.sched-day').value),
                    start_session_id: startId,
                    end_session_id: endId,
                    end_week: parseInt(card.querySelector('.sched-endweek').value),
                };
            });
        }

        function validate() {
            const title = document.getElementById('evTitle').value.trim();
            const startWeek = parseInt(document.getElementById('evStartWeek').value);
            if (!title) {
                showStatus('Please enter a course title.');
                return false;
            }
            if (startWeek < 1 || startWeek > 19) {
                showStatus('Start week must be 1–19.');
                return false;
            }

            const schedules = collectSchedules();
            if (!schedules.length) {
                showStatus('At least one class time is required.');
                return false;
            }

            for (const s of schedules) {
                if (s.end_week < startWeek || s.end_week > 19) {
                    showStatus(`End week must be between ${startWeek} and 19.`);
                    return false;
                }
            }

            const days = schedules.map(s => s.day_of_week);
            if (new Set(days).size !== days.length) {
                showStatus('Each class time must be on a different day.');
                return false;
            }

            return true;
        }

        // ── Save ──────────────────────────────────────────────
        async function saveEvent() {
            if (!validate()) return;
            const startWeek = parseInt(document.getElementById('evStartWeek').value);
            const payload = {
                title: document.getElementById('evTitle').value.trim(),
                admin_id: ADMIN_ID,
                schedules: collectSchedules().map(s => ({
                    ...s,
                    start_week: startWeek
                })),
            };
            setBusy(true);
            hideStatus();
            try {
                let saved;
                if (editingCourseId) {
                    saved = await apiFetch(`/course/${editingCourseId}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload)
                    });
                    removeCourseEvents(editingCourseId);
                    courseStore.delete(editingCourseId);
                } else {
                    saved = await apiFetch('/course/store', {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                }
                addCourseToCalendar(saved);
                closeModal();
            } catch (err) {
                showStatus(err.message || 'Something went wrong. Please try again.');
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
                    method: 'DELETE'
                });
                removeCourseEvents(editingCourseId);
                courseStore.delete(editingCourseId);
                closeModal();
            } catch (err) {
                showStatus(err.message || 'Could not delete. Please try again.');
            } finally {
                setBusy(false);
            }
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') saveEvent();
        });

        // ── FullCalendar ──────────────────────────────────────
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            initialDate: '2026-03-02', // open on week 1
            allDaySlot: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '22:00:00',
            scrollTime: '08:00:00',
            height: 'auto',
            editable: false,
            selectable: false,
            nowIndicator: true,
            validRange: {
                start: isoDate(SEMESTER_START),
                end: isoDate(new Date(SEMESTER_END.getTime() + 86400000)),
            },
            dayHeaderContent(arg) {
                const week = semesterWeekForDate(arg.date);
                const weekday = arg.text;
                return {
                    html: week ?
                        `${weekday}<div style="font-size:0.72em;color:#6b7280;margin-top:2px;">W${week}</div>` :
                        weekday,
                };
            },
            dayCellContent(arg) {
                const week = semesterWeekForDate(arg.date);
                return {
                    html: week ?
                        `${arg.dayNumberText}<div style="font-size:0.72em;color:#6b7280;margin-top:2px;">W${week}</div>` :
                        arg.dayNumberText,
                };
            },

            // NOTE: your GET controller needs ->with('schedules') added:
            // Course::where('admin_id', $admin_id)->with('schedules')->get();
            events(fetchInfo, successCallback, failureCallback) {
                console.log('📅 FullCalendar requesting events...');
                apiFetch(`/course?admin_id=${ADMIN_ID}`)
                    .then(courses => {
                        console.log('✅ Courses fetched:', courses);
                        if (!courses) {
                            console.warn('⚠️ No courses returned from API');
                            successCallback([]);
                            return;
                        }
                        courseStore.clear();
                        const allEvents = [];
                        courses.forEach(course => {
                            console.log('📌 Processing course:', course.id, course.title);
                            courseStore.set(course.id, course);
                            const events = courseToFcEvents(course);
                            console.log(`   Generated ${events.length} events for this course`);
                            courseToFcEvents(course).forEach(ev => allEvents.push(ev));
                        });
                        console.log(`📊 Total events to display: ${allEvents.length}`);
                        successCallback(allEvents);
                    })
                    .catch(err => {
                        console.error('❌ Failed to fetch courses:', err);
                        failureCallback(err);
                    });
            },

            dateClick(info) {
                const dow = jsDateToDayOfWeek(info.date);
                const pair = guessSessionPair(info.date);

                // Calculate the week based on the clicked date. 
                // If they click a date outside the semester, it safely falls back to week 1.
                const clickedWeek = semesterWeekForDate(info.date) || 1;

                openCreateModal({
                    dayOfWeek: dow,
                    startSessionId: pair.startId,
                    endSessionId: pair.endId,
                    startWeek: clickedWeek // <-- Pass the calculated week here
                });
            },

            eventClick(info) {
                openEditModal(info.event.extendedProps.courseId);
            },
        });

        calendar.render();
    </script>
</body>

</html>
