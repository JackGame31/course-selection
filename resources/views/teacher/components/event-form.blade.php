{{-- create event --}}
<dialog class="dialog" data-dialog="event-form">
    <form class="form" data-event-form>
        <div class="dialog__wrapper">
            {{-- header --}}
            <div class="dialog__header">
                <h2 class="dialog__title" data-dialog-title></h2>
                <button class="button button--icon button--secondary" type="button" data-dialog-close-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="dialog__content">
                <div class="form__fields">
                    {{-- user id --}}
                    <input type="hidden" id="admin_id" name="admin_id" value="{{ auth('admin')->id() }}" />

                    {{-- id --}}
                    <input type="hidden" id="id" name="id" />

                    {{-- title --}}
                    <div class="form__field">
                        <label class="form__label" for="title">Title</label>
                        <input class="input input--fill" id="title" name="title" type="text"
                            placeholder="My awesome event!" required autofocus />
                    </div>

                    {{-- day --}}
                    <div class="form__field">
                        <label class="form__label" for="day">Day</label>
                        <div class="select select--fill">
                            <select class="select__select" id="day" name="day" required>
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="select__icon">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>

                    <div class="form__split">
                        {{-- start time --}}
                        <div class="form__field">
                            <label class="form__label" for="start-time">Start time</label>
                            <div class="select select--fill">
                                <select class="select__select" id="start-time" name="start-time">
                                    <option value="0">12:00 AM</option>
                                    <option value="30">12:30 AM</option>
                                    <option value="60">1:00 AM</option>
                                    <option value="90">1:30 AM</option>
                                    <option value="120">2:00 AM</option>
                                    <option value="150">2:30 AM</option>
                                    <option value="180">3:00 AM</option>
                                    <option value="210">3:30 AM</option>
                                    <option value="240">4:00 AM</option>
                                    <option value="270">4:30 AM</option>
                                    <option value="300">5:00 AM</option>
                                    <option value="330">5:30 AM</option>
                                    <option value="360">6:00 AM</option>
                                    <option value="390">6:30 AM</option>
                                    <option value="420">7:00 AM</option>
                                    <option value="450">7:30 AM</option>
                                    <option value="480">8:00 AM</option>
                                    <option value="510">8:30 AM</option>
                                    <option value="540">9:00 AM</option>
                                    <option value="570">9:30 AM</option>
                                    <option value="600">10:00 AM</option>
                                    <option value="630">10:30 AM</option>
                                    <option value="660">11:00 AM</option>
                                    <option value="690">11:30 AM</option>
                                    <option value="720">12:00 PM</option>
                                    <option value="750">12:30 PM</option>
                                    <option value="780">1:00 PM</option>
                                    <option value="810">1:30 PM</option>
                                    <option value="840">2:00 PM</option>
                                    <option value="870">2:30 PM</option>
                                    <option value="900">3:00 PM</option>
                                    <option value="930">3:30 PM</option>
                                    <option value="960">4:00 PM</option>
                                    <option value="990">4:30 PM</option>
                                    <option value="1020">5:00 PM</option>
                                    <option value="1050">5:30 PM</option>
                                    <option value="1080">6:00 PM</option>
                                    <option value="1110">6:30 PM</option>
                                    <option value="1140">7:00 PM</option>
                                    <option value="1170">7:30 PM</option>
                                    <option value="1200">8:00 PM</option>
                                    <option value="1230">8:30 PM</option>
                                    <option value="1260">9:00 PM</option>
                                    <option value="1290">9:30 PM</option>
                                    <option value="1320">10:00 PM</option>
                                    <option value="1350">10:30 PM</option>
                                    <option value="1380">11:00 PM</option>
                                    <option value="1410">11:30 PM</option>
                                </select>

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="select__icon">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>

                        {{-- end time --}}
                        <div class="form__field">
                            <label class="form__label" for="end-time">End time</label>
                            <div class="select select--fill">
                                <select class="select__select" id="end-time" name="end-time">
                                    <option value="30">12:30 AM</option>
                                    <option value="60">1:00 AM</option>
                                    <option value="90">1:30 AM</option>
                                    <option value="120">2:00 AM</option>
                                    <option value="150">2:30 AM</option>
                                    <option value="180">3:00 AM</option>
                                    <option value="210">3:30 AM</option>
                                    <option value="240">4:00 AM</option>
                                    <option value="270">4:30 AM</option>
                                    <option value="300">5:00 AM</option>
                                    <option value="330">5:30 AM</option>
                                    <option value="360">6:00 AM</option>
                                    <option value="390">6:30 AM</option>
                                    <option value="420">7:00 AM</option>
                                    <option value="450">7:30 AM</option>
                                    <option value="480">8:00 AM</option>
                                    <option value="510">8:30 AM</option>
                                    <option value="540">9:00 AM</option>
                                    <option value="570">9:30 AM</option>
                                    <option value="600">10:00 AM</option>
                                    <option value="630">10:30 AM</option>
                                    <option value="660">11:00 AM</option>
                                    <option value="690">11:30 AM</option>
                                    <option value="720">12:00 PM</option>
                                    <option value="750">12:30 PM</option>
                                    <option value="780">1:00 PM</option>
                                    <option value="810">1:30 PM</option>
                                    <option value="840">2:00 PM</option>
                                    <option value="870">2:30 PM</option>
                                    <option value="900">3:00 PM</option>
                                    <option value="930">3:30 PM</option>
                                    <option value="960">4:00 PM</option>
                                    <option value="990">4:30 PM</option>
                                    <option value="1020">5:00 PM</option>
                                    <option value="1050">5:30 PM</option>
                                    <option value="1080">6:00 PM</option>
                                    <option value="1110">6:30 PM</option>
                                    <option value="1140">7:00 PM</option>
                                    <option value="1170">7:30 PM</option>
                                    <option value="1200">8:00 PM</option>
                                    <option value="1230">8:30 PM</option>
                                    <option value="1260">9:00 PM</option>
                                    <option value="1290">9:30 PM</option>
                                    <option value="1320">10:00 PM</option>
                                    <option value="1350">10:30 PM</option>
                                    <option value="1380">11:00 PM</option>
                                    <option value="1410">11:30 PM</option>
                                    <option value="1440">12:00 AM</option>
                                </select>

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="select__icon">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- confirmation --}}
            <div class="dialog__footer">
                <div class="dialog__actions">
                    <button class="button button--secondary" type="button" data-dialog-close-button>Cancel</button>
                    <button class="button button--primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</dialog>
