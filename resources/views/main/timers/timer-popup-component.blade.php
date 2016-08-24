<script id="timer-popup-template" type="x-template">

    <div>
        <popup
            :show-popup.sync="showPopup"
            id="timer-popup"
            :redirect-to="redirectTo"
            :update="updateTimer"
            :destroy="deleteTimer"
        >
            <div slot="content">
                <div class="form-group">
                    <label for="selected-timer-start">Start</label>
                    <input
                            v-model="selectedTimer.start"
                            type="text"
                            id="selected-timer-start"
                            name="selected-timer-start"
                            placeholder="start"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-timer-finish">Finish</label>
                    <input
                            v-model="selectedTimer.finish"
                            type="text"
                            id="selected-timer-finish"
                            name="selected-timer-finish"
                            placeholder="finish"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-timer-activity">Activity</label>
                    {{--Using id and not object so the activity doesn't change until the user clicks the 'save' button--}}
                    <select
                            v-model="selectedTimer.activity.data.id"
                            id="selected-timer-activity"
                            class="form-control"
                    >
                        <option
                                v-for="activity in activities"
                                v-bind:value="activity.id"
                        >
                            @{{ activity.name }}
                        </option>
                    </select>
                </div>
            </div>
        </popup>

    </div>

</script>