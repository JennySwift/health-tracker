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

<div v-show="!timerInProgress" id="new-manual-timer">

    <div class="form-group">
        <label for="new-manual-timer-activity">Activity</label>

        <select
            v-model="newManualTimer.activity.id"
            id="new-manual-timer-activity"
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
        <label for="new-manual-timer-start">Start</label>
        <input
            v-model="newManualTimer.start"
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
            type="text"
            id="new-manual-timer-finish"
            name="new-manual-timer-finish"
            placeholder="finish"
            class="form-control"
        >
    </div>

    <div class="form-group">
        <button v-on: click="insertManualTimer()" class="btn btn-success">Add manual time entry</button>
    </div>


</div>