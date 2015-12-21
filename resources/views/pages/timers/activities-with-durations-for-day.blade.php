<div id="activities-with-durations-for-day">
    <span
        ng-repeat="activity in activitiesWithDurationsForDay | filter: activitiesFilter"
        ng-style="{'background': activity.color}"
        class="label label-default">
        [[activity.name]] [[activity.totalMinutesForDay | formatDurationFilter]]</span>
</div>