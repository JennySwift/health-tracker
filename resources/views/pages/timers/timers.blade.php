<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="TimersController">

	@include('templates.header')

	<div class="container" id="timers-page">

        {{--@include('templates.date-navigation')--}}

        <div id="new-timer">
            <label for="activity">Activity</label>

            <select
                    ng-options="activity.id as activity.name for activity in activities"
                    ng-model="newTimer.activity.id"
                    id="new-timer-activity"
                    class="form-control">
            </select>

            <button ng-click="startTimer()" class="btn btn-success">Start</button>
            <button ng-click="stopTimer()" class="btn btn-danger">Stop</button>
        </div>

        <div id="timer-filter">
            <label>Filter timers</label>
            <input ng-model="timersFilter" type="text"/>
        </div>

        <ul id="timers" class="list-group">
            <li ng-repeat="timer in timers | filter: filterTimers" class="list-group-item timer">
                <div>
                    <span ng-style="{'background': timer.activity.data.color}" class="label">[[timer.activity.data.name]]</span>
                </div>

                <div>
                    <span>[[timer.minutes]]</span>
                </div>

                <div>
                    <span>[[timer.start]]</span>
                    <span> - </span>
                    <span>[[timer.finish]]</span>
                </div>

            </li>
        </ul>

	</div>

	@include('templates.footer')

</body>
</html>
