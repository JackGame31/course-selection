import { validateEvent, generateEventId } from "./event.js";

export function initEventForm(toaster) {
    const formElement = document.querySelector("[data-event-form]");

    let mode = "create";

    formElement.addEventListener("submit", (event) => {
        event.preventDefault();
        const formEvent = formIntoEvent(formElement);
        const validationError = validateEvent(formEvent);
        if (validationError !== null) {
            toaster.error(validationError);
            return;
        }

        if (mode === "create") {
            formElement.dispatchEvent(
                new CustomEvent("event-create", {
                    detail: {
                        event: formEvent,
                    },
                    bubbles: true,
                })
            );
        }

        if (mode === "edit") {
            formElement.dispatchEvent(
                new CustomEvent("event-edit", {
                    detail: {
                        event: formEvent,
                    },
                    bubbles: true,
                })
            );
        }
    });

    return {
        formElement,
        switchToCreateMode(day, startTime, endTime) {
            mode = "create";
            fillFormWithDay(formElement, day, startTime, endTime);
        },
        switchToEditMode(event) {
            mode = "edit";
            fillFormWithEvent(formElement, event);
        },
        reset() {
            formElement.querySelector("#id").value = null;
            formElement.reset();
        },
    };
}

function fillFormWithDay(formElement, day, startTime, endTime) {
    const dayInputelement = formElement.querySelector("#day");
    const startTimeSelectElement = formElement.querySelector("#start-time");
    const endTimeSelectElement = formElement.querySelector("#end-time");

    dayInputelement.value = day;
    startTimeSelectElement.value = startTime;
    endTimeSelectElement.value = endTime;
}

function fillFormWithEvent(formElement, event) {
    const idInputElement = formElement.querySelector("#id");
    const titleInputElement = formElement.querySelector("#title");
    const dayInputElement = formElement.querySelector("#day");
    const startTimeSelectElement = formElement.querySelector("#start-time");
    const endTimeSelectElement = formElement.querySelector("#end-time");

    idInputElement.value = event.id;
    titleInputElement.value = event.title;
    dayInputElement.value = event.day;
    startTimeSelectElement.value = event.startTime;
    endTimeSelectElement.value = event.endTime;
}

function formIntoEvent(formElement) {
    const formData = new FormData(formElement);
    const admin_id = formData.get("admin_id");
    const id = formData.get("id");
    const title = formData.get("title");
    const day = formData.get("day");
    const startTime = formData.get("start-time");
    const endTime = formData.get("end-time");
    const color = formData.get("color") || getRandomDarkColor();

    const event = {
        id: id ? Number.parseInt(id, 10) : generateEventId(),
        admin_id: Number.parseInt(admin_id, 10),
        title,
        day: Number.parseInt(day, 10),
        startTime: Number.parseInt(startTime, 10),
        endTime: Number.parseInt(endTime, 10),
        color: color,
    };

    return event;
}

function getLuminance(hex) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);

    // perceived brightness formula
    return 0.299 * r + 0.587 * g + 0.114 * b;
}

function getRandomDarkColor(maxLuminance = 180) {
    let color;

    do {
        color = `#${Math.floor(Math.random() * 16777215)
            .toString(16)
            .padStart(6, "0")}`;
    } while (getLuminance(color) > maxLuminance);

    return color;
}
