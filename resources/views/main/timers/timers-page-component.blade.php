<script id="timers-page-template" type="x-template">

    <div id="timers-page">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        @include('main.timers.new-timer')
        @include('main.timers.timer-in-progress')
        @include('main.timers.activities-with-durations-for-day')
        @include('main.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('main.timers.activities-with-durations-for-week')
                    {{--@include('pages.timers.timer-filter')--}}
            @include('main.timers.timers')
        </div>

    </div>

</script>