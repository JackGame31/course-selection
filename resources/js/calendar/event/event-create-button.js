import { getUrlDay } from "../utilization/url";

export function initEventCreateButtons() {
    const buttonElements = document.querySelectorAll(
        "[data-event-create-button]"
    );

    for (const buttonElement of buttonElements) {
        initEventCreateButton(buttonElement);
    }
}

function initEventCreateButton(buttonElement) {
    let selectedDay = getUrlDay();

    buttonElement.addEventListener("click", () => {
        buttonElement.dispatchEvent(
            new CustomEvent("event-create-request", {
                detail: {
                    day: selectedDay,
                    startTime: 600,
                    endTime: 960,
                },
                bubbles: true,
            })
        );
    });

    document.addEventListener("day-change", (event) => {
        selectedDay = event.detail.day;
    });
}
