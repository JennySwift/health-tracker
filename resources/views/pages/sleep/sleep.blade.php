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
                <div ng-style="{'bottom': 1 * 60 / 2 + 'px'}">1:00am</div>
                <div ng-style="{'bottom': 2 * 60 / 2 + 'px'}">2:00am</div>
                <div ng-style="{'bottom': 3 * 60 / 2 + 'px'}">3:00am</div>
                <div ng-style="{'bottom': 4 * 60 / 2 + 'px'}">4:00am</div>
                <div ng-style="{'bottom': 5 * 60 / 2 + 'px'}">5:00am</div>
                <div ng-style="{'bottom': 6 * 60 / 2 + 'px'}">6:00am</div>
                <div ng-style="{'bottom': 7 * 60 / 2 + 'px'}">7:00am</div>
                <div ng-style="{'bottom': 8 * 60 / 2 + 'px'}">8:00am</div>
                <div ng-style="{'bottom': 9 * 60 / 2 + 'px'}">9:00am</div>
                <div ng-style="{'bottom': 10 * 60 / 2 + 'px'}">10:00am</div>
                <div ng-style="{'bottom': 11 * 60 / 2 + 'px'}">11:00am</div>
                <div ng-style="{'bottom': 12 * 60 / 2 + 'px'}">12:00pm</div>
                <div ng-style="{'bottom': 13 * 60 / 2 + 'px'}">1:00pm</div>
                <div ng-style="{'bottom': 14 * 60 / 2 + 'px'}">2:00pm</div>
                <div ng-style="{'bottom': 15 * 60 / 2 + 'px'}">3:00pm</div>
                <div ng-style="{'bottom': 16 * 60 / 2 + 'px'}">4:00pm</div>
                <div ng-style="{'bottom': 17 * 60 / 2 + 'px'}">5:00pm</div>
                <div ng-style="{'bottom': 18 * 60 / 2 + 'px'}">6:00pm</div>
                <div ng-style="{'bottom': 19 * 60 / 2 + 'px'}">7:00pm</div>
                <div ng-style="{'bottom': 20 * 60 / 2 + 'px'}">8:00pm</div>
                <div ng-style="{'bottom': 21 * 60 / 2 + 'px'}">9:00pm</div>
                <div ng-style="{'bottom': 22 * 60 / 2 + 'px'}">10:00pm</div>
                <div ng-style="{'bottom': 23 * 60 / 2 + 'px'}">11:00pm</div>
                <div ng-style="{'bottom': 24 * 60 / 2 + 'px'}">12:00am</div>
            </div>

            <div id="sleep-chart-entries">
                <div ng-repeat="date in entries" class="date-entries">
                    <div ng-repeat="entry in date">

                        <div
                            ng-if="!entry.fakeStartPosition"
                            ng-style="{'bottom': entry.startPosition/ 2 + 'px', 'height': entry.startHeight / 2 + 'px'}"
                            class="time start">
                            <label class="label label-success">[[entry.start]]</label>
                        </div>

                        <div
                            ng-if="!entry.fakeStartPosition"
                            ng-style="{'bottom': entry.finishPosition/ 2 + 'px'}"
                            class="time finish">
                            <label class="label label-success">[[entry.finish]]</label>
                        </div>

                        <div ng-if="entry.fakeStartPosition || entry.fakeStartPosition === 0"
                            ng-style="{'bottom': entry.fakeStartPosition + 'px', 'height': entry.startHeight / 2 + 'px'}"
                            class="time start">
                            {{--<label class="label label-danger">[[entry.fakeStart]]</label>--}}
                        </div>

                    </div>
                    <div class="date">
                        <label class="label label-primary">[[date.date]]</label>
                    </div>
                </div>
            </div>


        </div>

	</div>

	@include('templates.footer')

</body>
</html>
