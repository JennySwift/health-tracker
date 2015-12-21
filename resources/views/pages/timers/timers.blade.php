<div id="timers">
    <table class="table table-bordered">

        <thead>
        <tr>
            <th>Activity</th>
            <th>Duration</th>
            <th>Duration today</th>
            <th>Start</th>
            <th>End</th>
        </tr>
        </thead>

        <tbody>
        <tr ng-repeat="timer in timers | filter: activitiesFilter | orderBy: 'start':true" class="timer">
            <td>
                <i ng-click="deleteTimer(timer)" class="fa fa-times .delete"></i>
                <span ng-style="{'background': timer.activity.data.color}" class="label">[[timer.activity.data.name]]</span>
            </td>

            <td class="duration">
                <span>[[timer.hours | doubleDigitsFilter]]:[[timer.minutes | doubleDigitsFilter]]</span>
            </td>

            <td>
                <span>[[timer.durationInMinutesForDay | formatDurationFilter]]</span>
            </td>

            <td>
                <span>[[timer.start | formatDateTimeFilter:'hoursAndMinutes' ]]</span>
                <span class="seconds">:[[timer.start | formatDateTimeFilter:'seconds']]</span>
            </td>

            <td>
                <span>[[timer.finish | formatDateTimeFilter:'hoursAndMinutes']]</span>
                <span class="seconds">:[[timer.finish | formatDateTimeFilter:'seconds']]</span>
            </td>

        </tr>
        </tbody>

    </table>
</div>