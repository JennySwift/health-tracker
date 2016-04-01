
<div id="new-sleep-entry">
    <div>
        <input v-model="newSleepEntry.start" v-on:keyup.13="insertSleepEntry()" type="text" placeholder="start">
    </div>

    <div>
        <label>Started yesterday</label>
        <input v-model="newSleepEntry.startedYesterday" v-on:keyup.13="insertSleepEntry()" type="checkbox">
    </div>

    <div>
        <input v-model="newSleepEntry.finish" v-on:keyup.13="insertSleepEntry()" type="text" placeholder="finish">
    </div>

    <div>
        <button v-on:click="insertSleepEntry()" class="btn btn-success">Add sleep entry</button>
    </div>


</div>
