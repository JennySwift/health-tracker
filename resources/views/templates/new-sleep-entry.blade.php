
<div id="new-sleep-entry">
    <div>
        <input v-model="newSleepEntry.start" type="text" placeholder="start">
    </div>

    <div>
        <label>Started yesterday</label>
        <input v-model="newSleepEntry.startedYesterday" type="checkbox">
    </div>

    <div>
        <input v-model="newSleepEntry.finish" type="text" placeholder="finish">
    </div>

    <div>
        <button v-on:click="insertSleepEntry()" class="btn btn-success">Add sleep entry</button>
    </div>


</div>
