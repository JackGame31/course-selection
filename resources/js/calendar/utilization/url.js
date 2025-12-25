import { today } from "./date.js";

export function initUrl() {
    let selectedView = getUrlView();
    let selectedDay = getUrlDay();

    function updateUrl() {
        const url = new URL(window.location);

        // Store the actual date for functionality
        url.searchParams.set("view", selectedView);
        url.searchParams.set("day", selectedDay);

        history.replaceState(null, "", url);
    }

    document.addEventListener("view-change", (event) => {
        selectedView = event.detail.view;
        updateUrl();
    });

    document.addEventListener("day-change", (event) => {
        selectedDay = event.detail.day;
        updateUrl();
    });
}

export function getUrlView() {
    const urlParams = new URLSearchParams(window.location.search);

    return urlParams.get("view") || "week";
}

export function getUrlDay() {
    const urlParams = new URLSearchParams(window.location.search);
    const day = parseInt(urlParams.get("day"), 10);

    return day ? day : today().getDay();
}
