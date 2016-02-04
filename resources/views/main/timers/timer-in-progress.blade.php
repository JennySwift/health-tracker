<div v-show="timerInProgress" id="timer-in-progress">
    <div v-if="timerInProgress.activity">@{{ timerInProgress.activity.data.name }}</div>
    <div v-show="showTimerInProgress" id="timer-clock"></div>
    <button v-show="showTimerInProgress" v-on:click="stopTimer()" class="btn btn-danger">Stop</button>
    <button v-on:click="showTimerInProgress = !showTimerInProgress" class="btn btn-default">Toggle visibility</button>
</div>