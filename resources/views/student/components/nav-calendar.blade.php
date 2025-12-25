{{-- day info --}}
<div class="nav__day-info">
    <div class="nav__controls">
        <button class="button button--secondary desktop-only" data-nav-today-button>Today</button>
        <button class="button button--icon button--secondary mobile-only" data-nav-today-button>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="button__icon">
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <rect width="18" height="18" x="3" y="4" rx="2" />
                <path d="M3 10h18" />
                <path d="M8 14h.01" />
                <path d="M12 14h.01" />
                <path d="M16 14h.01" />
                <path d="M8 18h.01" />
                <path d="M12 18h.01" />
                <path d="M16 18h.01" />
            </svg>
        </button>

        <div class="nav__arrows">
            <button class="button button--icon button--secondary" data-nav-previous-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="button__icon">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>

            <button class="button button--icon button--secondary" data-nav-next-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="button__icon">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>
    <time class="nav__day" data-nav-day></time>
</div>

{{-- select view --}}
<div class="select desktop-only">
    <select class="select__select" data-view-select>
        <option value="day">Day</option>
        <option value="week" selected>Week</option>
    </select>

    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="select__icon">
        <path d="m6 9 6 6 6-6" />
    </svg>
</div>
