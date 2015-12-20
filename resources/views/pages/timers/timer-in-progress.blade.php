<div ng-show="timerInProgress" id="timer-in-progress">
    <div>[[timerInProgress.activity.data.name]]</div>
    <div id="timer-clock"></div>
    <button ng-click="stopTimer()" class="btn btn-danger">Stop</button>
</div>