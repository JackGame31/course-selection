// calendar
import { initCalendar } from "../calendar/calendar";
import { initViewSelect } from "../calendar/view-select";
import { initNav } from "../calendar/nav";
import { initUrl } from "../calendar/utilization/url";
import { initResponsive } from "../calendar/utilization/responsive";
import { initNotifications } from "../calendar/notifications";

// course
import { initEventStore } from "./course-list";
import { initCourseList } from "./course-list";
import { initCourseAssignmentDialog } from "./course-details-dialog";
import { initCourseSubmitButton } from "./course-submit-button";

// event
let eventStore = initEventStore();
initCourseSubmitButton(eventStore);
initCourseAssignmentDialog();
initCourseList();

// calendar
initCalendar(eventStore);
initNotifications();
initResponsive();
initViewSelect();
initUrl();
initNav();
