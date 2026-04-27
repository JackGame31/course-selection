// ── Course List Functions ──────────────────────────────
import { seededColor } from "./calendar-colors.js";
export async function loadTeacherCourses() {
    try {
        const courses = await apiFetch(`/course?admin_id=${ADMIN_ID}`);
        courseStore.clear();
        courses.forEach((course) => courseStore.set(course.id, course));
        renderCourseList();
        updateCourseCount();
    } catch (err) {
        console.error("Failed to load courses:", err);
        document.getElementById("courseList").innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <p class="text-sm">Failed to load courses</p>
            </div>
        `;
    }
}

export function renderCourseList() {
    const courseList = document.getElementById("courseList");
    const courses = Array.from(courseStore.values());

    if (courses.length === 0) {
        courseList.innerHTML = `
            <div class="text-center py-8 text-[#6b6560]">
                <p class="text-sm">No courses yet</p>
                <p class="text-xs mt-1">Create your first course</p>
            </div>
        `;
        return;
    }

    courseList.innerHTML = courses
        .map((course) => {
            const color = seededColor(course.id);
            const scheduleText =
                course.schedules?.length > 0
                    ? `${course.schedules.length} session${course.schedules.length > 1 ? "s" : ""}`
                    : "No schedule";

            return `
            <div class="course-card p-3 bg-[#fdfcf9] border border-[#e8e3db] rounded-lg cursor-pointer"
                 onclick="handleCourseCardClick(${course.id})">
                <div class="flex items-start gap-3">
                    <div class="w-3 h-3 rounded-full flex-shrink-0 mt-1" style="background-color: ${color}"></div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-[#1a1714] text-sm truncate">${course.title}</h4>
                        <p class="text-xs text-[#6b6560] mt-1">${scheduleText}</p>
                    </div>
                    <button onclick="event.stopPropagation(); handleEditClick(${course.id})"
                        class="flex-shrink-0 p-1 text-[#6b6560] hover:text-[#1a1714] hover:bg-[#f7f4ef] rounded transition-colors"
                        title="Edit course">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M8.5 2.5L9.5 3.5L4 9L2.5 9.5L3 8L8.5 2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.5 3.5L8.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        })
        .join("");
}

export function handleCourseCardClick(courseId) {
    // Remove selected class from all cards
    document.querySelectorAll(".course-card").forEach((card) => {
        card.classList.remove("selected");
    });

    // Add selected class to clicked card
    event.currentTarget.classList.add("selected");

    // Navigate calendar to the first event of this course
    const course = courseStore.get(courseId);
    if (course && course.schedules && course.schedules.length > 0) {
        // Find the schedule with the earliest start week
        const earliestSchedule = course.schedules.reduce(
            (earliest, current) => {
                return current.start_week < earliest.start_week
                    ? current
                    : earliest;
            },
        );

        // Calculate the date of the first class
        const firstClassDate = scheduleDate(
            earliestSchedule.start_week,
            earliestSchedule.day_of_week,
        );

        // Navigate calendar to that date (keeps current view)
        calendar.gotoDate(firstClassDate);
    }
}

export function handleEditClick(courseId) {
    // Open edit modal
    openEditModal(courseId);
}

export function updateCourseCount() {
    const count = courseStore.size;
    document.getElementById("courseCount").textContent =
        `${count} course${count !== 1 ? "s" : ""}`;
}
