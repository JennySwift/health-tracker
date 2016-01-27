<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
	<meta charset="UTF-8">
	<title>tracker</title>
	@include('templates.shared.head-links')
</head>
<body ng-controller="TimersController">

	@include('templates.shared.header')
    <feedback-directive></feedback-directive>
    @include('templates.shared.loading')

	<div class="container" id="timers-page">

        {{--@include('templates.date-navigation')--}}
        <date-navigation-directive date="date"></date-navigation-directive>

        @include('pages.timers.new-timer')
        @include('pages.timers.timer-in-progress')
        @include('pages.timers.activities-with-durations-for-day')
        @include('pages.timers.activities-filter')

        <div id="activities-and-timers-container">
            @include('pages.timers.activities-with-durations-for-week')
            {{--        @include('pages.timers.timer-filter')--}}
            @include('pages.timers.timers')
        </div>

	</div>

	@include('templates.shared.footer')

</body>
</html>
