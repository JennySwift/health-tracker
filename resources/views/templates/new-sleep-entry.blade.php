
<div id="new-sleep-entry">
    <div>
        <input ng-model="newSleepEntry.start" type="text" placeholder="start">
    </div>

    <div>
        <label>Started yesterday</label>
        <input ng-model="newSleepEntry.startedYesterday" type="checkbox">
    </div>

    <div>
        <input ng-model="newSleepEntry.finish" type="text" placeholder="finish">
    </div>

    <div>
        <button ng-click="insertSleepEntry()" class="btn btn-success">Add sleep entry</button>
    </div>


</div>
