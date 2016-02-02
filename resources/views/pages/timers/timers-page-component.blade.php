<script id="timers-page-template" type="x-template">

    <div class="container" id="timers-page">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        @include('pages.timers.new-timer')
        @include('pages.timers.timer-in-progress')
        @include('pages.timers.activities-with-durations-for-day')
        @include('pages.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('pages.timers.activities-with-durations-for-week')
            {{--        @include('pages.timers.timer-filter')--}}
            @include('pages.timers.timers')
        </div>

    </div>

</script>