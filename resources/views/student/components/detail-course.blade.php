{{-- detail course --}}
<dialog class="dialog" data-dialog="course-assignment">
    <div class="dialog__wrapper">
        <div class="dialog__header">
            <h2 class="dialog__title">Course Assignment</h2>
            <div class="dialog__header-actions">
                {{-- close button --}}
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
            <div class="course-assignment-details" data-course-details>
                <div class="course-assignment__line" style="background-color: var(--course-color)" data-course-color>
                </div>
                <div class="course-assignment__content">
                    <div class="course-assignment__title" data-course-title></div>
                    <div class="course-assignment__teacher text-sm" data-course-teacher></div>
                    <div class="course-assignment__time">
                        <time data-course-details-day></time> <br />
                        <time data-course-start-time></time> - <time data-course-end-time></time>
                    </div>
                </div>
            </div>
        </div>

        <div class="dialog__footer">
            @if (!$isFinish)
                <button class="button button--primary" data-course-assign-button>
                    Assign Course
                </button>
                <button class="button button--danger" data-course-unassign-button>
                    Unassign Course
                </button>
            @endif
            <button class="button button--secondary" data-dialog-close-button>
                Cancel
            </button>
        </div>
    </div>
</dialog>
