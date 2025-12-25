import { isTheSameDay } from "../utilization/date.js";

export function initEventStore() {
    document.addEventListener("event-create", async (e) => {
        const eventData = e.detail.event;
        try {
            const response = await fetch("/api/course/", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(eventData),
            });

            if (!response.ok) {
                throw new Error("Failed to create event");
            }

            // toast notification
            document.dispatchEvent(
                new CustomEvent("toast-create", { bubbles: true })
            );

            // update calendar
            document.dispatchEvent(
                new CustomEvent("events-change", { bubbles: true })
            );
        } catch (error) {
            console.error(error);
            alert("Error saving event");
        }
    });

    document.addEventListener("event-delete", async (event) => {
        const deletedEvent = event.detail.event;
        try {
            const response = await fetch("/api/course/" + deletedEvent.id, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            if (!response.ok) {
                throw new Error("Failed to delete event");
            }

            // toast notification
            document.dispatchEvent(
                new CustomEvent("toast-delete", { bubbles: true })
            );

            // update calendar
            document.dispatchEvent(
                new CustomEvent("events-change", { bubbles: true })
            );
        } catch (error) {
            console.error(error);
            alert("Error delete event");
        }
    });

    document.addEventListener("event-edit", async (event) => {
        const editedEvent = event.detail.event;
        try {
            const response = await fetch("/api/course/" + editedEvent.id, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(editedEvent),
            });

            if (!response.ok) {
                throw new Error("Failed to update event");
            }

            // toast notification
            document.dispatchEvent(
                new CustomEvent("toast-edit", { bubbles: true })
            );

            // update calendar
            document.dispatchEvent(
                new CustomEvent("events-change", { bubbles: true })
            );
        } catch (error) {
            console.error(error);
            alert("Error delete event");
        }
    });

    return {
        getEventsByDay: async (day) => {
            try {
                const events = await getEventsFromDatabase(admin_id);
                const filteredEvents = events.filter((event) =>
                    isTheSameDay(event.day, day)
                );

                return filteredEvents;
            } catch (error) {
                console.error(error);
                return [];
            }
        },
    };
}

async function getEventsFromDatabase(admin_id) {
    try {
        const response = await fetch("/api/course?admin_id=" + admin_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch events from server");
        }

        const events = await response.json();

        return events;
    } catch (error) {
        console.error(error);
        throw error; // Re-throw to handle in callers
    }
}
