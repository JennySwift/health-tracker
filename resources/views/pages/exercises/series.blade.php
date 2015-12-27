<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="UTF-8">
    <title>tracker</title>
    @include('templates.head-links')
</head>
<body>

@include('templates.header')

<div ng-controller="SeriesController" id="exercise-series" class="container">

    @include('pages.exercises.index')

    <div>
        <div class="margin-bottom">
            <h2 class="center">Series</h2>
            <input ng-model="newSeries.name" ng-keyup="insertExerciseSeries($event.keyCode)" type="text" placeholder="Add a new series"  id="exercise-series" class="form-control">
        </div>

        <input ng-model="seriesPriorityFilter" type="text" placeholder="filter by priority">

        <div ng-controller="ExerciseEntriesController">
            @include('pages.entries.popups.exercise-entries')
            @include('pages.entries.exercise-entry-inputs')
            @include('pages.entries.exercise-entries')
        </div>

        <div class="series-exercises-container">

            <div>
                <table id="series-table" class="table table-bordered">
                    <tr>
                        <th>series</th>
                        <th>days ago</th>
                        <th>history</th>
                        <th>workouts</th>
                        <th>priority</th>
                        <th>edit</th>
                        <th>x</th>
                    </tr>
                    <tr ng-repeat="series in exercise_series | orderBy: 'lastDone' | filter:{'priority': seriesPriorityFilter}" ng-class="{'selected': series.id === selected.exercise_series.id}">
                        <td ng-click="getExercisesInSeries(series)" class="name">[[series.name]]</td>
                        <td ng-click="getExercisesInSeries(series)" class="name">[[series.lastDone]]</td>
                        <td><button ng-click="getExerciseSeriesHistory(series)">show</button></td>
                        <td><span ng-repeat="workout in series.workouts.data" class="label label-default">[[workout.name]]</span></td>
                        <td>[[series.priority]]</td>
                        <td><button ng-click="showExerciseSeriesPopup(series)" class="btn-xs">edit</button></td>
                        <td><i ng-click="deleteExerciseSeries(series)" class="delete-item fa fa-times"></i></td>
                    </tr>
                </table>
            </div>

            <div>
                <table id="exercises-table" class="table">
                    <tr>
                        <th>Step</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Target</th>
                        <th>Priority</th>
                        <th>Program</th>
                        <th>Days ago</th>
                        <th>Add</th>
                    </tr>
                    <tr ng-repeat="exercise in selected.exercise_series.exercises.data | filter:{'priority': seriesPriorityFilter}">
                        <td ng-click="showExercisePopup(exercise)">[[exercise.stepNumber]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.name]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.description]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.target]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.priority]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.program.name]]</td>
                        <td ng-click="showExercisePopup(exercise)">[[exercise.lastDone]]</td>
                        <td>
                            <button ng-click="insertExerciseSet(exercise)" class="btn btn-default btn-xs">Add set</button>
                        </td>
                    </tr>
                </table>

                <p ng-if="selected.exercise_series.exercises.data.length === 0">No exercises in the series</p>

            </div>
        </div>


    </div>

</div>

@include('templates.footer')

</body>
</html>