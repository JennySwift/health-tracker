<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
   @include('templates.head-links')
</head>
<body>

@include('templates.header')

<div ng-controller="workouts" class="container">

    <div id="workouts">
        <h2 class="center">Workouts</h2>
        <input ng-keyup="insertWorkout($event.keyCode)" type="text" placeholder="Add a new workout" id="workout" class="form-control">
        <div class="flex">
            <div ng-repeat="workout in workouts">
                <h3 class="center">[[workout.name]]</h3>
                <ul class="list-group">
                    <li ng-repeat="series in workout.contents" class="list-group-item">[[series.name]]</li>
                </ul>
            </div>
        </div>
    </div>

</div>

@include('templates.footer')

</body>
</html>