<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="SleepController">

	@include('templates.header')

	<div class="container">

        {{--@include('templates.date-navigation')--}}

        <h1>sleep</h1>

		<div id="sleep">
            <div ng-repeat="entry in entries">[[entry.start]] [[entry.finish]] [[entry.startDate]] [[entry.hours]]h[[entry.minutes]]m</div>
        </div>

	</div>

	@include('templates.footer')

</body>
</html>
