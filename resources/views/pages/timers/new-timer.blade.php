<div ng-show="!timerInProgress" id="new-timer">
    <select
            ng-options="activity.id as activity.name for activity in activities"
            ng-model="newTimer.activity.id"
            id="new-timer-activity"
            class="form-control">
    </select>

    <button ng-click="startTimer()" class="btn btn-success">Start</button>

</div>

<div id="new-manual-timer">
    <select
            ng-options="activity.id as activity.name for activity in activities"
            ng-model="newManualTimer.activity.id"
            id="new-manual-timer-activity"
            class="form-control">
    </select>

    <input ng-model="newManualTimer.start" type="text" placeholder="start">
    <input ng-model="newManualTimer.finish" type="text" placeholder="finish">

    <button ng-click="insertManualTimer()" class="btn btn-success">Add manual time entry</button>
</div>