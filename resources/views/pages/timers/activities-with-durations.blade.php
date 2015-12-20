<div id="activities-with-durations">
    <span ng-repeat="activity in activitiesWithDurations" ng-style="{'background': activity.color}" class="label label-default">[[activity.name]] [[activity.hoursForDay | doubleDigitsFilter]]:[[activity.minutesForDay | doubleDigitsFilter]]</span>
</div>