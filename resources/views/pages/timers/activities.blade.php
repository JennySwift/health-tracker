<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.head-links')
</head>
<body ng-controller="ActivitiesController">

	@include('templates.header')

	<div class="container">

        {{--@include('templates.date-navigation')--}}

		<h1>Activities</h1>

        <div id="activities">
            <div ng-repeat="activity in activities">[[activity.name]]</div>
        </div>
	</div>

	@include('templates.footer')

</body>
</html>
