<script id="new-sleep-entry-template" type="x-template">

    <div id="new-sleep-entry">
        <div class="form-group">
            <label for="new-sleep-entry-start">Start</label>
            <input
                    v-model="newSleepEntry.start"
                    v-on:keyup.13="insertSleepEntry()"
                    type="text"
                    id="new-sleep-entry-start"
                    name="new-sleep-entry-start"
                    placeholder="start"
                    class="form-control"
            >
        </div>

        <div class="checkbox-container">
            <input
                    v-model="newSleepEntry.startedYesterday"
                    v-on:keyup.13="insertSleepEntry()"
                    id="new-sleep-entry-started-yesterday"
                    type="checkbox"
            >
            <label for="new-sleep-entry-started-yesterday">Started yesterday</label>
        </div>

        <div class="form-group">
            <label for="new-sleep-entry-finish">Finish</label>
            <input
                    v-model="newSleepEntry.finish"
                    v-on:keyup.13="insertSleepEntry()"
                    type="text"
                    id="new-sleep-entry-finish"
                    name="new-sleep-entry-finish"
                    placeholder="finish"
                    class="form-control"
            >
        </div>

        <div class="form-group">
            <button v-on:click="insertSleepEntry()" class="btn btn-success">Add sleep entry</button>
        </div>

    </div>

</script>

