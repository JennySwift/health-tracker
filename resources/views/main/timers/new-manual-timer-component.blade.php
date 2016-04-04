<script id="new-manual-timer-template" type="x-template">

    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="new-manual-timer-popup" class="popup-inner">

            <div class="content">
                <div class="form-group">
                    <label for="new-manual-timer-activity">Activity</label>

                    <select
                            v-model="newManualTimer.activity"
                            id="new-manual-timer-activity"
                            class="form-control"
                    >
                        <option
                                v-for="activity in activities"
                                v-bind:value="activity"
                        >
                            @{{ activity.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new-manual-timer-start">Start</label>
                    <input
                            v-model="newManualTimer.start"
                            v-on:keyup.13="insertManualTimer()"
                            type="text"
                            id="new-manual-timer-start"
                            name="new-manual-timer-start"
                            placeholder="start"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="new-manual-timer-finish">Finish</label>
                    <input
                            v-model="newManualTimer.finish"
                            v-on:keyup.13="insertManualTimer()"
                            type="text"
                            id="new-manual-timer-finish"
                            name="new-manual-timer-finish"
                            placeholder="finish"
                            class="form-control"
                    >
                </div>

            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" v-link="{path: '/timers'}" class="btn btn-default">Cancel</button>
                <button v-on:click="insertManualTimer()" class="btn btn-success">Insert manual timer</button>
            </div>

        </div>
    </div>


</script>