<dialog class="dialog" data-dialog="event-delete">
    <div class="dialog__wrapper">
        <div class="dialog__header">
            <h2 class="dialog__title">Delete event</h2>

            <button class="button button--icon button--secondary" data-dialog-close-button autofocus>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="button__icon">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="dialog__content">
            <p>Do you really want to delete <strong data-event-delete-title></strong>?</p>
        </div>

        <div class="dialog__footer">
            <div class="dialog__actions">
                <button class="button button--secondary" data-dialog-close-button>Cancel</button>
                <button class="button button--danger" data-event-delete-button>Delete</button>
            </div>
        </div>
    </div>
</dialog>
