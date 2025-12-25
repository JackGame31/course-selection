import { initToaster } from "../components/toaster.js";

export function initNotifications() {
    const toaster = initToaster(document.body);

    document.addEventListener("toast-create", () => {
        toaster.success("Event has been created");
    });

    document.addEventListener("toast-delete", () => {
        toaster.success("Event has been deleted");
    });

    document.addEventListener("toast-edit", () => {
        toaster.success("Event has been edited");
    });
}
