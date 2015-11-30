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

		<div id="sleep-chart">

            <div id="sleep-chart-times">
                <div ng-style="{'top': 1 * 60 / 2 + 'px'}">1:00am</div>
                <div ng-style="{'top': 2 * 60 / 2 + 'px'}">2:00am</div>
                <div ng-style="{'top': 3 * 60 / 2 + 'px'}">3:00am</div>
                <div ng-style="{'top': 4 * 60 / 2 + 'px'}">4:00am</div>
                <div ng-style="{'top': 5 * 60 / 2 + 'px'}">5:00am</div>
                <div ng-style="{'top': 6 * 60 / 2 + 'px'}">6:00am</div>
                <div ng-style="{'top': 7 * 60 / 2 + 'px'}">7:00am</div>
                <div ng-style="{'top': 8 * 60 / 2 + 'px'}">8:00am</div>
                <div ng-style="{'top': 9 * 60 / 2 + 'px'}">9:00am</div>
                <div ng-style="{'top': 10 * 60 / 2 + 'px'}">10:00am</div>
                <div ng-style="{'top': 11 * 60 / 2 + 'px'}">11:00am</div>
                <div ng-style="{'top': 12 * 60 / 2 + 'px'}">12:00pm</div>
                <div ng-style="{'top': 13 * 60 / 2 + 'px'}">1:00pm</div>
                <div ng-style="{'top': 14 * 60 / 2 + 'px'}">2:00pm</div>
                <div ng-style="{'top': 15 * 60 / 2 + 'px'}">3:00pm</div>
                <div ng-style="{'top': 16 * 60 / 2 + 'px'}">4:00pm</div>
                <div ng-style="{'top': 17 * 60 / 2 + 'px'}">5:00pm</div>
                <div ng-style="{'top': 18 * 60 / 2 + 'px'}">6:00pm</div>
                <div ng-style="{'top': 19 * 60 / 2 + 'px'}">7:00pm</div>
                <div ng-style="{'top': 20 * 60 / 2 + 'px'}">8:00pm</div>
                <div ng-style="{'top': 21 * 60 / 2 + 'px'}">9:00pm</div>
                <div ng-style="{'top': 22 * 60 / 2 + 'px'}">10:00pm</div>
                <div ng-style="{'top': 23 * 60 / 2 + 'px'}">11:00pm</div>
                <div ng-style="{'top': 24 * 60 / 2 + 'px'}">12:00am</div>
            </div>

            <div id="sleep-chart-entries">
                <div ng-repeat="date in entries" class="date-entries">
                    <div class="date">[[date.date]]</div>
                    <div ng-repeat="entry in date">
                        <div ng-style="{'top': entry.startRelativeHeight / 2 + 'px'}" class="time">[[entry.start]]</div>
                        <div ng-style="{'top': entry.finishRelativeHeight / 2 + 'px'}" class="time">[[entry.finish]]</div>
                    </div>
                </div>
            </div>


        </div>

	</div>

	@include('templates.footer')

</body>
</html>
