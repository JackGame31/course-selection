<dialog class="dialog" data-dialog="event-details">
    <div class="dialog__wrapper">
        <div class="dialog__header">
            <h2 class="dialog__title">Event details</h2>
            <div class="dialog__header-actions">
                <button class="button button--icon button--secondary" data-event-details-delete-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <path d="M3 6h18" />
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                    </svg>
                </button>

                <button class="button button--icon button--secondary" data-event-details-edit-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <path
                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                        <path d="m15 5 4 4" />
                    </svg>
                </button>

                <div class="dialog__header-actions-divider"></div>

                <button class="button button--icon button--secondary" data-dialog-close-button autofocus>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="dialog__content">
            <div class="event-details" data-event-details>
                <div class="event-details__line"></div>
                <div class="event-details__content">
                    <div class="event-details__title" data-event-details-title></div>
                    <div class="event-details__time">
                        <time data-event-details-date></time> <br />
                        <time data-event-details-start-time></time> - <time data-event-details-end-time></time>
                    </div>
                </div>
            </div>
        </div>
    </div>
</dialog>
