<script id="timers-page-template" type="x-template">

    <div id="timers-page">

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        <timer-popup
                :activities="activities"
                :timers.sync="timers"
        >
        </timer-popup>

        <new-timer
                :activities="activities"
                :timers.sync="timers"
        >
        </new-timer>

        {{--<button v-on:click="showNewManualTimerPopup()" class="btn btn-default">Manual Timer</button>--}}

        <router-view
                :activities="activities"
                :timers.sync="timers"
                :date="date"
        >
        </router-view>
        {{--<new-manual-timer--}}
                {{--:activities="activities"--}}
                {{--:timers.sync="timers"--}}
                {{--:date="date"--}}
        {{-->--}}
        {{--</new-manual-timer>--}}

        @include('main.timers.activities-with-durations-for-day')
        @include('main.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('main.timers.timers')
            @include('main.timers.activities-with-durations-for-week')
        </div>

    </div>

</script>