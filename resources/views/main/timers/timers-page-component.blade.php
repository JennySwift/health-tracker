<script id="timers-page-template" type="x-template">

    <div id="timers-page">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        <timer-popup></timer-popup>

        <new-timer
                :activities="activities"
                :timer-in-progress="timerInProgress"
                :show-timer-in-progress="showTimerInProgress"
                :timers.sync="timers"
        >
        </new-timer>

        <new-manual-timer
                :activities="activities"
                :timers.sync="timers"
                :date="date"
        >
        </new-manual-timer>

        @include('main.timers.activities-with-durations-for-day')
        @include('main.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('main.timers.timers')
            @include('main.timers.activities-with-durations-for-week')
        </div>

    </div>

</script>