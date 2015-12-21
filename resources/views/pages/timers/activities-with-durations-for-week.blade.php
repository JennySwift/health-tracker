<div id="activities-with-durations-for-week">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Activity</th>
                <th>Duration for week</th>
                <th>Avg/day for week</th>
                <th>Duration for all time</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="activity in activitiesWithDurationsForWeek | filter: activitiesFilter">
                <td>[[activity.name]]</td>
                <td>[[activity.totalMinutesForWeek | formatDurationFilter]]</td>
                <td>[[activity.averageMinutesPerDayForWeek | formatDurationFilter]]</td>
                <td>
                    <div ng-if="activity.totalMinutesForAllTime">
                        [[activity.totalMinutesForAllTime | formatDurationFilter]]
                    </div>
                </td>
            </tr>
        </tbody>

    </table>
</div>