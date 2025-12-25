{{-- week calendar --}}
<template data-template="week-calendar">
    <div class="week-calendar" data-week-calendar>
        <ul class="week-calendar__day-of-week-list" data-week-calendar-day-of-week-list>
        </ul>

        <ul class="week-calendar__all-day-list" data-week-calendar-all-day-list>
        </ul>

        <div class="week-calendar__content">
            <div class="week-calendar__content-inner" data-calendar-scrollable>
                <ul class="week-calendar__time-list">
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">12:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">1:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">2:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">3:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">4:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">5:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">6:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">7:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">8:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">9:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">10:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">11:00 AM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">12:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">1:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">2:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">3:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">4:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">5:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">6:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">7:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">8:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">9:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">10:00 PM</time>
                    </li>
                    <li class="week-calendar__time-item">
                        <time class="week-calendar__time">11:00 PM</time>
                    </li>
                </ul>

                <div class="week-calendar__columns" data-week-calendar-columns>
                </div>
            </div>
        </div>
    </div>
</template>

{{-- day of week: to keep list of days in week --}}
<template data-template="week-calendar-day-of-week">
    <li class="week-calendar__day-of-week" data-week-calendar-day-of-week>
        <button class="week-calendar__day-of-week-button" data-week-calendar-day-of-week-button>
            <span class="week-calendar__day-of-week-day" data-week-calendar-day-of-week-day></span>
        </button>
    </li>
</template>

{{-- all day list item: to keep all day events --}}
<template data-template="week-calendar-all-day-list-item">
    <li class="week-calendar__all-day-list-item" data-week-calendar-all-day-list-item>
        <ul class="event-list" data-event-list></ul>
    </li>
</template>

{{-- week calendar column: to keep column --}}
<template data-template="week-calendar-column">
    <div class="week-calendar__column" data-week-calendar-column>
        <div class="week-calendar__cell" data-week-calendar-cell="0"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="60"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="120"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="180"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="240"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="300"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="360"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="420"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="480"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="540"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="600"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="660"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="720"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="780"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="840"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="900"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="960"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1020"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1080"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1140"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1200"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1260"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1320"></div>
        <div class="week-calendar__cell" data-week-calendar-cell="1380"></div>
    </div>
</template>

{{-- event list item --}}
<template data-template="event-list-item">
    <li class="event-list__item" data-event-list-item>
    </li>
</template>

{{-- event --}}
<template data-template="event">
    <button class="event" data-event>
        <span class="event__color"></span>
        <span class="event__title" data-event-title></span>
        <span class="event__time">
            <time data-event-start-time></time> - <time data-event-end-time></time>
        </span>
    </button>
</template>
