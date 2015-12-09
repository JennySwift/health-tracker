<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="TimersController">

	@include('templates.header')

	<div class="container">

        {{--@include('templates.date-navigation')--}}

		<h1>Timers</h1>

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

	@include('templates.footer')

</body>
</html>
