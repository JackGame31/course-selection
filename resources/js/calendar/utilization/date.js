export const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
];

export function today() {
    const now = new Date();

    return new Date(now.getFullYear(), now.getMonth(), now.getDate(), 12);
}

export function addMonths(date, months) {
    const firstDayOfMonth = new Date(
        date.getFullYear(),
        date.getMonth() + months,
        1,
        date.getHours()
    );
    const lastDayOfMonth = getLastDayOfMonthDate(firstDayOfMonth);

    const dayOfMonth = Math.min(date.getDate(), lastDayOfMonth.getDate());

    return new Date(
        date.getFullYear(),
        date.getMonth() + months,
        dayOfMonth,
        date.getHours()
    );
}

export function addDays(day, days) {
    return (((day + days) % 7) + 7) % 7;
}

export function subtractDays(day, days) {
    return addDays(day, -days);
}

export function generateMonthCalendarDays(currentDate) {
    const calendarDays = [];

    const lastDayOfPreviousMonthDate = getLastDayOfMonthDate(
        subtractMonths(currentDate, 1)
    );

    const lastDayOfPreviousMonthWeekDay = lastDayOfPreviousMonthDate.getDay();
    if (lastDayOfPreviousMonthWeekDay !== 6) {
        for (let i = lastDayOfPreviousMonthWeekDay; i >= 0; i -= 1) {
            const calendarDay = subtractDays(lastDayOfPreviousMonthDate, i);
            calendarDays.push(calendarDay);
        }
    }

    const lastDayOfCurrentMonthDate = getLastDayOfMonthDate(currentDate);
    for (let i = 1; i <= lastDayOfCurrentMonthDate.getDate(); i += 1) {
        const calendarDay = addDays(lastDayOfPreviousMonthDate, i);
        calendarDays.push(calendarDay);
    }

    const totalWeeks = Math.ceil(calendarDays.length / 7);
    const totalDays = totalWeeks * 7;
    const missingDayAmount = totalDays - calendarDays.length;
    for (let i = 1; i <= missingDayAmount; i += 1) {
        const calendarDay = addDays(lastDayOfCurrentMonthDate, i);
        calendarDays.push(calendarDay);
    }

    return calendarDays;
}

export function isTheSameDay(dayA, dayB) {
    return dayA === dayB;
}

function getLastDayOfMonthDate(date) {
    return new Date(date.getFullYear(), date.getMonth() + 1, 0, 12);
}
