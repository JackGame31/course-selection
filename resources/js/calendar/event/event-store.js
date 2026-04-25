import { isTheSameDay } from "../utilization/date.js";

export function initEventStore() {
    let allEvents = [];
    let isLoaded = false;

    /* ---------------------------
       FETCH & CACHE
    ---------------------------- */
    async function loadEvents({ notify = false } = {}) {
        const response = await fetch("/api/course?admin_id=" + admin_id, {
            headers: { "Content-Type": "application/json" },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch events");
        }

        allEvents = await response.json();
        isLoaded = true;

        if (notify) {
            document.dispatchEvent(
                new CustomEvent("events-change", { bubbles: true })
            );
        }
    }

    async function reloadEvents() {
        await loadEvents();
        document.dispatchEvent(
            new CustomEvent("events-change", { bubbles: true })
        );
    }

    /* ---------------------------
       INIT LOAD (once)
    ---------------------------- */
    loadEvents({ notify: true }).catch(console.error);

    /* ---------------------------
       CREATE
    ---------------------------- */
    document.addEventListener("event-create", async (e) => {
        try {
            const response = await fetch("/api/course/store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(e.detail.event),
            });

            if (!response.ok) throw new Error("Create failed");

            document.dispatchEvent(
                new CustomEvent("toast-create", { bubbles: true })
            );

            await reloadEvents();
        } catch (err) {
            console.error(err);
        }
    });

    /* ---------------------------
       DELETE
    ---------------------------- */
    document.addEventListener("event-delete", async (e) => {
        try {
            const response = await fetch("/api/course/" + e.detail.event.id, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            if (!response.ok) throw new Error("Delete failed");

            document.dispatchEvent(
                new CustomEvent("toast-delete", { bubbles: true })
            );

            await reloadEvents();
        } catch (err) {
            console.error(err);
        }
    });

    /* ---------------------------
       EDIT
    ---------------------------- */
    document.addEventListener("event-edit", async (e) => {
        try {
            const response = await fetch("/api/course/" + e.detail.event.id, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(e.detail.event),
            });

            if (!response.ok) throw new Error("Edit failed");

            document.dispatchEvent(
                new CustomEvent("toast-edit", { bubbles: true })
            );

            await reloadEvents();
        } catch (err) {
            console.error(err);
        }
    });

    /* ---------------------------
       PUBLIC API (SYNC)
    ---------------------------- */
    return {
        getEventsByDay(day) {
            if (!isLoaded) return [];

            return allEvents.filter((event) => isTheSameDay(event.day, day));
        },

        getAllEvents() {
            return allEvents;
        },
    };
}
