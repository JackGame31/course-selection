{{-- submit course --}}
<dialog class="dialog" data-dialog="course-submit">
    <div class="dialog__wrapper">
        <div class="dialog__header">
            <h2 class="dialog__title">Submit Course Selection</h2>

            {{-- close button --}}
            <button class="button button--icon button--secondary" data-dialog-close-button autofocus>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="button__icon">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        {{-- form --}}
        <form id="course-submit-form" action="{{ route('student.submitCourseSelection') }}" method="POST">
            @csrf
            <div class="dialog__content">
                <p>Are you sure you want to submit the course selection?</p>

                <br>

                {{-- list of selected class --}}
                <ul class="course-submit__list"></ul>

                <br>

                {{-- hidden id container --}}
                <div id="course-id-container" style="display: none;"></div>
            </div>

            <div class="dialog__footer">
                <div class="dialog__actions">
                    <button type="button" class="button button--secondary" data-dialog-close-button>Cancel</button>
                    <button type="submit" class="button button--primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</dialog>
