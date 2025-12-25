import { isTheSameDay } from "../calendar/utilization/date";

export function initEventStore() {
    const checkboxes = document.querySelectorAll(".sidebar .course-checkbox");
    const detailButtons = document.querySelectorAll(".detail-btn");
    const isFinish = document.querySelector("#classList").dataset.isfinish;

    const allEvents = [];
    let selectedEvents = [];

    // Add event to the events array
    function addEvent(item) {
        selectedEvents.push({
            id: Number(item.dataset.id),
            title: item.dataset.title,
            teacher: item.dataset.teacher,
            day: Number(item.dataset.day),
            startTime: Number(item.dataset.start),
            endTime: Number(item.dataset.end),
            color: item.dataset.color,
        });
    }

    // Remove event from the events array
    function removeEvent(id) {
        const index = selectedEvents.findIndex((e) => e.id === id);
        if (index !== -1) selectedEvents.splice(index, 1);
    }

    // Populate allEvents with data from all checkboxes
    checkboxes.forEach((checkbox) => {
        const item = checkbox.closest(".class-item");
        allEvents.push({
            id: Number(item.dataset.id),
            title: item.dataset.title,
            teacher: item.dataset.teacher,
            day: Number(item.dataset.day),
            startTime: Number(item.dataset.start),
            endTime: Number(item.dataset.end),
            color: item.dataset.color,
        });
    });
    // if user already select course, meaning all event is selected
    if (isFinish) {
        selectedEvents = allEvents;
    }

    // Handle checkbox change
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", (e) => {
            const item = e.target.closest(".class-item");
            const id = Number(item.dataset.id);
            const selected = e.target.checked;

            if (selected) {
                addEvent(item);
            } else {
                removeEvent(id);
            }

            // Dispatch custom event to notify of changes
            document.dispatchEvent(
                new CustomEvent("events-selected", {
                    bubbles: true,
                })
            );
        });
    });

    // Handle detail button click (for modal functionality)
    detailButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            const id = Number(e.target.dataset.id);

            // Find the course using the ID
            const course = allEvents.find((event) => {
                return event.id === id;
            });

            if (course) {
                // Dispatch the 'course-click' event with course details
                let event = course;
                document.dispatchEvent(
                    new CustomEvent("event-click", {
                        detail: { event },
                        bubbles: true,
                    })
                );
            } else {
                console.error(`Course with ID ${id} not found`);
            }
        });
    });

    // PUBLIC API for getting events by day (if needed later)
    return {
        getEventsByDay(day) {
            return selectedEvents.filter((event) =>
                isTheSameDay(event.day, day)
            );
        },
        getAllSelectedEvents() {
            return selectedEvents;
        },
        getAllEvents() {
            return allEvents;
        },
    };
}

export function initCourseList() {
    // Select all checkboxes in both mobile and desktop sidebars
    const mobileCheckboxes = document.querySelectorAll(
        ".dialog .course-checkbox"
    );
    const desktopCheckboxes = document.querySelectorAll(
        ".desktop-only .course-checkbox"
    );

    // Function to sync the checkboxes in both sidebars
    function syncCheckboxes(sourceCheckbox, sourceSidebar) {
        // Get the ID of the checkbox being checked
        const courseId = sourceCheckbox.dataset.id;
        const isChecked = sourceCheckbox.checked;

        // Determine the target checkboxes (opposite sidebar)
        const targetSidebar =
            sourceSidebar === "mobile" ? desktopCheckboxes : mobileCheckboxes;

        // Find the corresponding checkbox in the other sidebar using the same data-id
        const targetCheckbox = Array.from(targetSidebar).find(
            (checkbox) => checkbox.dataset.id === courseId
        );

        if (targetCheckbox) {
            targetCheckbox.checked = isChecked;
        }
    }

    // Add event listeners to the checkboxes in both sidebars
    mobileCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", (e) => {
            syncCheckboxes(e.target, "mobile");
        });
    });

    desktopCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", (e) => {
            syncCheckboxes(e.target, "desktop");
        });
    });
}
