<script id="timers-page-template" type="x-template">

    <div id="timers-page">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        <timer-popup></timer-popup>

        @include('main.timers.new-timer')
        @include('main.timers.timer-in-progress')
        @include('main.timers.activities-with-durations-for-day')
        @include('main.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('main.timers.timers')
            @include('main.timers.activities-with-durations-for-week')
        </div>

    </div>

</script>