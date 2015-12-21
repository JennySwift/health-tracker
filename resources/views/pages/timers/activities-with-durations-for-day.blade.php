<div id="activities-with-durations-for-day">
    <span ng-repeat="activity in activitiesWithDurationsForDay" ng-style="{'background': activity.color}" class="label label-default">[[activity.name]] [[activity.hoursForDay | doubleDigitsFilter]]:[[activity.minutesForDay | doubleDigitsFilter]]</span>
</div>