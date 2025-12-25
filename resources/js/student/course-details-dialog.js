import { initDialog } from "../components/dialog.js";
import { eventTimeToDate } from "../calendar/event/event.js";
import { days } from "../calendar/utilization/date.js";

const courseTimeFormatter = new Intl.DateTimeFormat("en-US", {
    hour: "numeric",
    minute: "numeric",
});

export function initCourseAssignmentDialog() {
    const dialog = initDialog("course-assignment");

    const assignButtonElement = dialog.dialogElement.querySelector(
        "[data-course-assign-button]"
    );
    const unassignButtonElement = dialog.dialogElement.querySelector(
        "[data-course-unassign-button]"
    );

    let currentCourse = null;

    // Listen for a custom event to open the dialog with the course details
    document.addEventListener("event-click", (event) => {
        currentCourse = event.detail.event;
        fillCourseAssignmentDialog(dialog.dialogElement, currentCourse);
        dialog.open();
    });

    // Handle button click logic
    if (assignButtonElement)
        assignButtonElement.addEventListener("click", () =>
            handleAssignUnassignButtonClick(currentCourse, true, dialog)
        );

    if (unassignButtonElement)
        unassignButtonElement.addEventListener("click", () =>
            handleAssignUnassignButtonClick(currentCourse, false, dialog)
        );
}

function handleAssignUnassignButtonClick(course, isAssigning, dialog) {
    const checkboxId = course.id;
    const checkboxes = document.querySelectorAll(".course-checkbox");

    // Update checkbox based on the action (assign or unassign)
    checkboxes.forEach((checkbox) => {
        const item = checkbox.closest(".class-item");
        const id = Number(item.dataset.id);
        if (id === Number(checkboxId)) {
            checkbox.checked = isAssigning;
        }
    });

    // Close dialog after action
    dialog.close();
}

function fillCourseAssignmentDialog(parent, course) {
    const courseDetailsElement = parent.querySelector("[data-course-details]");
    const courseDetailsTitleElement = courseDetailsElement.querySelector(
        "[data-course-title]"
    );
    const courseDetailsTeacherElement = courseDetailsElement.querySelector(
        "[data-course-teacher]"
    );
    const courseDetailsDayElement = courseDetailsElement.querySelector(
        "[data-course-details-day]"
    );
    const courseDetailsStartTimeElement = courseDetailsElement.querySelector(
        "[data-course-start-time]"
    );
    const courseDetailsEndTimeElement = courseDetailsElement.querySelector(
        "[data-course-end-time]"
    );
    const courseDetailsColorElement = courseDetailsElement.querySelector(
        "[data-course-color]"
    );

    // Fill the dialog with the course data
    courseDetailsTitleElement.textContent = course.title;
    courseDetailsTeacherElement.textContent = course.teacher;
    courseDetailsDayElement.textContent = days[course.day];
    courseDetailsStartTimeElement.textContent = courseTimeFormatter.format(
        eventTimeToDate(course.startTime)
    );
    courseDetailsEndTimeElement.textContent = courseTimeFormatter.format(
        eventTimeToDate(course.endTime)
    );
    courseDetailsColorElement.style.backgroundColor = course.color;

    // Update button display based on course assignment
    updateAssignUnassignButtonState(parent, course);
}

function updateAssignUnassignButtonState(parent, course) {
    const assignButtonElement = parent.querySelector(
        "[data-course-assign-button]"
    );
    const unassignButtonElement = parent.querySelector(
        "[data-course-unassign-button]"
    );

    const isAssigned = checkIfCourseAssigned(course.id);

    if (assignButtonElement && unassignButtonElement)
        if (isAssigned) {
            assignButtonElement.style.display = "none";
            unassignButtonElement.style.display = "inline";
            unassignButtonElement.dataset.id = course.id;
        } else {
            assignButtonElement.style.display = "inline";
            unassignButtonElement.style.display = "none";
            assignButtonElement.dataset.id = course.id;
        }
}

function checkIfCourseAssigned(courseId) {
    const checkboxes = document.querySelectorAll(".course-checkbox");
    let isAssigned = false;

    checkboxes.forEach((checkbox) => {
        const id = Number(checkbox.closest(".class-item").dataset.id);
        if (id === courseId) {
            isAssigned = checkbox.checked;
        }
    });

    return isAssigned;
}
