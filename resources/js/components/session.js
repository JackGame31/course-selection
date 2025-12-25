import { initToaster } from "./toaster.js";

export function initSession(sessionMessages) {
    const toaster = initToaster(document.body);

    if (sessionMessages.success) toaster.success(sessionMessages.success);
    if (sessionMessages.error) toaster.error(sessionMessages.error);
}
