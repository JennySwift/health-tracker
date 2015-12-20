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
        <date-navigation-directive date="date"></date-navigation-directive>

        @include('pages.timers.new-timer')
        @include('pages.timers.timer-in-progress')
        @include('pages.timers.timer-filter')
        @include('pages.timers.activities-with-durations')
        @include('pages.timers.timers')

	</div>

	@include('templates.footer')

</body>
</html>
