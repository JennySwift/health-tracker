<script id="new-timer-template" type="x-template">

    <div>

        <div v-show="!timerInProgress" id="new-timer">
            <div class="form-group">
                <label for="activity">Activity</label>

                <select
                        v-model="newTimer.activity.id"
                        id="new-timer-activity"
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

            <div class="form-group">
                <button v-on:click="startTimer()" class="btn btn-success">Start</button>
            </div>
        </div>

        <div v-show="timerInProgress" id="timer-in-progress">
            <div v-if="timerInProgress.activity">@{{ timerInProgress.activity.data.name }}</div>
            <div v-show="showTimerInProgress" id="timer-clock"></div>
            <button v-show="showTimerInProgress" v-on:click="stopTimer()" class="btn btn-danger">Stop</button>
            <button v-on:click="showTimerInProgress = !showTimerInProgress" class="btn btn-default">Toggle visibility</button>
        </div>
    </div>

</script>