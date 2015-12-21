<div ng-show="timerInProgress" id="timer-in-progress">
    <div>[[timerInProgress.activity.data.name]]</div>
    <div ng-show = "showTimerInProgress" id="timer-clock"></div>
    <button ng-show = "showTimerInProgress" ng-click="stopTimer()" class="btn btn-danger">Stop</button>
    <button ng-click="showTimerInProgress = !showTimerInProgress" class="btn btn-default">Toggle visibility</button>
</div>