<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    <?php
    include(base_path().'/resources/views/templates/config.php');
    include($head_links);
    ?>
</head>
<body>

@include('templates.header')

<div ng-controller="workouts" id="exercises" class="container">

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

<?php
include($footer);

?>

@include('footer')

</body>
</html>