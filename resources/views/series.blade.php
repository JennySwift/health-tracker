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

<div ng-controller="SeriesController" id="exercises" class="container">

    <?php include($templates . '/exercises/index.php'); ?>

    <div>
        <div class="margin-bottom">
            <h2 class="center">Series</h2>
            <input ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="Add a new series"  id="exercise-series" class="form-control">
        </div>

        <table class="table table-bordered">
            <tr>
                <th>series</th>
                <th>history</th>
                <th>workout</th>
                <th>edit</th>
                <th>x</th>
            </tr>
            <tr ng-repeat="series in exercise_series">
                <td>[[series.name]]</td>
                <td><button ng-click="getExerciseSeriesHistory(series.id)">show</button></td>
                <td><span ng-repeat="workout in series.workouts">[[workout.name]]</span></td>
                <td><button ng-click="showExerciseSeriesPopup(series)" class="btn-xs">edit</button></td>
                <td><i ng-click="deleteExerciseSeries(series)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>

</div>

<?php
include($footer);

?>

@include('footer')

</body>
</html>