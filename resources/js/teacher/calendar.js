// event
import { initEventCreateButtons } from "../calendar/event/event-create-button.js";
import { initEventDeleteDialog } from "../calendar/event/event-delete-dialog.js";
import { initEventDetailsDialog } from "../calendar/event/event-details-dialog.js";
import { initEventFormDialog } from "../calendar/event/event-form-dialog.js";
import { initEventStore } from "../calendar/event/event-store.js";

// calendar related
import { initNav } from "../calendar/nav.js";
import { initCalendar } from "../calendar/calendar.js";
import { initViewSelect } from "../calendar/view-select.js";
import { initNotifications } from "../calendar/notifications.js";
import { initResponsive } from "../calendar/utilization/responsive.js";
import { initUrl } from "../calendar/utilization/url.js";

// event
const eventStore = initEventStore();
initEventCreateButtons();
initEventDeleteDialog();
initEventDetailsDialog();
initEventFormDialog();

// calendar
initCalendar(eventStore);
initNotifications();
initViewSelect();
initUrl();
initResponsive();
initNav();
