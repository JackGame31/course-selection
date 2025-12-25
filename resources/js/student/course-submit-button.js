import { eventTimeToDate } from "../calendar/event/event";
import { initDialog } from "../components/dialog";
import { days } from "../calendar/utilization/date";

const eventTimeFormatter = new Intl.DateTimeFormat("en-US", {
    hour: "numeric",
    minute: "numeric",
});

export function initCourseSubmitButton(eventStore) {
    const buttonElements = document.querySelectorAll(
        "[data-course-submit-button]"
    );
    const dialog = initDialog("course-submit");

    buttonElements.forEach((button) => {
        button.addEventListener("click", (event) => {
            let selectedCourses = eventStore.getAllSelectedEvents();

            // if selected event is empty, call notification
            if (selectedCourses.length === 0) {
                document.dispatchEvent(
                    new CustomEvent("toast-error", {
                        detail: {
                            message:
                                "No courses selected to submit. Please select courses first.",
                        },
                        bubbles: true,
                    })
                );
                return;
            }

            // fill in the dialog
            fillCourseSubmitDialog(selectedCourses);

            // open the dialog
            dialog.open();
        });
    });
}

function fillCourseSubmitDialog(selectedCourses) {
    const dialog = document.querySelector("[data-dialog='course-submit']");
    const courseSubmitList = dialog.querySelector(".course-submit__list");
    const courseIdContainer = document.getElementById("course-id-container");

    if (!dialog || !courseSubmitList || !courseIdContainer) return;

    courseSubmitList.innerHTML = "";
    courseIdContainer.innerHTML = "";

    // Compute conflicted courses
    const conflictedIds = getConflictedCourseIds(selectedCourses);

    // Sort courses by day and start time
    selectedCourses.sort((a, b) => b.day - a.day || b.startTime - a.startTime);

    // Render courses
    selectedCourses.forEach((course) => {
        const listItemElement = document.createElement("li");
        listItemElement.classList.add("course-item");
        listItemElement.style.setProperty("--course-color", course.color);

        // format time
        const startTime = eventTimeFormatter.format(
            eventTimeToDate(course.startTime)
        );
        const endTime = eventTimeFormatter.format(
            eventTimeToDate(course.endTime)
        );

        // conflict check
        const isConflicted = conflictedIds.has(course.id);
        if (isConflicted) listItemElement.classList.add("conflict");

        // create spans
        const titleSpan = document.createElement("span");
        titleSpan.classList.add("course-title");
        titleSpan.textContent = course.title;

        const daySpan = document.createElement("span");
        daySpan.classList.add("course-day");
        daySpan.textContent = days[course.day];

        const timeSpan = document.createElement("span");
        timeSpan.classList.add("course-time");
        timeSpan.textContent = `${startTime} - ${endTime}`;

        listItemElement.append(titleSpan, daySpan, timeSpan);

        // warning span for conflict
        if (isConflicted) {
            const warningSpan = document.createElement("span");
            warningSpan.classList.add("course-warning");
            warningSpan.textContent = "⚠ Time conflict";
            listItemElement.appendChild(warningSpan);
        }

        courseSubmitList.appendChild(listItemElement);

        // hidden input for form submission
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "selectedCourses[]";
        hiddenInput.value = course.id;
        courseIdContainer.appendChild(hiddenInput);
    });

    // show dialog
    if (typeof dialog.showModal === "function") {
        dialog.showModal();
    } else {
        dialog.classList.add("open");
    }
}

function hasTimeConflict(courseA, courseB) {
    return (
        courseA.day === courseB.day &&
        courseA.startTime < courseB.endTime &&
        courseA.endTime > courseB.startTime
    );
}

function getConflictedCourseIds(courses) {
    const conflictedIds = new Set();

    for (let i = 0; i < courses.length; i++) {
        for (let j = i + 1; j < courses.length; j++) {
            if (hasTimeConflict(courses[i], courses[j])) {
                conflictedIds.add(courses[i].id);
                conflictedIds.add(courses[j].id);
            }
        }
    }

    return conflictedIds;
}
