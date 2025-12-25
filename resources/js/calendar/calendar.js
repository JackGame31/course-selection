import { initWeekCalendar } from "./week-calendar.js";
import { currentDeviceType } from "./utilization/responsive.js";
import { getUrlView, getUrlDay } from "./utilization/url.js";

export function initCalendar(eventStore) {
    const calendarElement = document.querySelector("[data-calendar]");

    let selectedView = getUrlView();
    let selectedDay = getUrlDay();
    let deviceType = currentDeviceType();

    async function refreshCalendar() {
        const calendarScrollableElement = calendarElement.querySelector(
            "[data-calendar-scrollable]"
        );

        const spinnerElement = document.querySelector("[data-spinner]");
        if (spinnerElement) {
            spinnerElement.style.removeProperty("display");
        }

        const scrollTop =
            calendarScrollableElement === null
                ? 0
                : calendarScrollableElement.scrollTop;

        calendarElement.replaceChildren();

        if (selectedView === "week") {
            await initWeekCalendar(
                calendarElement,
                selectedDay,
                eventStore,
                false,
                deviceType
            );
        } else {
            await initWeekCalendar(
                calendarElement,
                selectedDay,
                eventStore,
                true,
                deviceType
            );
        }

        calendarElement
            .querySelector("[data-calendar-scrollable]")
            .scrollTo({ top: scrollTop });

        spinnerElement.style.display = "none";
    }

    document.addEventListener("view-change", (event) => {
        selectedView = event.detail.view;
        refreshCalendar();
    });

    document.addEventListener("day-change", (event) => {
        selectedDay = event.detail.day;
        refreshCalendar();
    });

    document.addEventListener("device-type-change", (event) => {
        deviceType = event.detail.deviceType;
        refreshCalendar();
    });

    document.addEventListener("events-change", () => {
        refreshCalendar();
    });

    document.addEventListener("events-selected", () => {
        refreshCalendar();
    });

    refreshCalendar();
}
