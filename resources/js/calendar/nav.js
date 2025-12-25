import { today, addDays, subtractDays, days } from "./utilization/date.js";
import { getUrlDay, getUrlView } from "./utilization/url.js";
import { currentDeviceType } from "./utilization/responsive.js";

export function initNav() {
    const todayButtonElement = document.querySelector(
        "[data-nav-today-button]"
    );
    const previousButtonElement = document.querySelector(
        "[data-nav-previous-button]"
    );
    const nextButtonElement = document.querySelector("[data-nav-next-button]");
    const dayElement = document.querySelector("[data-nav-day]");

    let selectedView = getUrlView();
    let selectedDay = getUrlDay();
    let selectedDeviceType = currentDeviceType();

    function updateNavVisibility() {
        const isMobile = selectedDeviceType === "mobile";
        const shouldShow = isMobile ? true : selectedView === "day";

        // only for day desktop view
        todayButtonElement.style.display =
            selectedView === "day" ? "block" : "none";

        // mobile or day desktop view
        previousButtonElement.style.display = shouldShow ? "flex" : "none";
        nextButtonElement.style.display = shouldShow ? "flex" : "none";
        dayElement.style.display = shouldShow ? "block" : "none";
    }

    todayButtonElement.addEventListener("click", () => {
        todayButtonElement.dispatchEvent(
            new CustomEvent("day-change", {
                detail: {
                    day: today().getDay(),
                },
                bubbles: true,
            })
        );
    });

    previousButtonElement.addEventListener("click", () => {
        previousButtonElement.dispatchEvent(
            new CustomEvent("day-change", {
                detail: {
                    day: getPreviousDay(selectedDay),
                },
                bubbles: true,
            })
        );
    });

    nextButtonElement.addEventListener("click", () => {
        nextButtonElement.dispatchEvent(
            new CustomEvent("day-change", {
                detail: {
                    day: getNextDay(selectedDay),
                },
                bubbles: true,
            })
        );
    });

    document.addEventListener("view-change", (event) => {
        selectedView = event.detail.view;
        updateNavVisibility();
    });

    document.addEventListener("device-type-change", (event) => {
        selectedDeviceType = event.detail.deviceType;
        updateNavVisibility();
    });

    document.addEventListener("day-change", (event) => {
        selectedDay = event.detail.day;
        refreshDayElement(dayElement, selectedDay);
    });

    updateNavVisibility();
    refreshDayElement(dayElement, selectedDay);
}

function refreshDayElement(dayElement, selectedDay) {
    dayElement.textContent = days[selectedDay];
}

function getPreviousDay(selectedDay) {
    return subtractDays(selectedDay, 1);
}

function getNextDay(selectedDay) {
    return addDays(selectedDay, 1);
}
