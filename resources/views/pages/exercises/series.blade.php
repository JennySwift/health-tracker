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
        <div class="top-buttons">
            @include('pages.exercises.new-series')
            @include('pages.exercises.new-exercise')
            <div>
                <input ng-model="seriesPriorityFilter" type="text" placeholder="filter by priority">
            </div>

        </div>

        <div ng-controller="ExerciseEntriesController">
            @include('pages.entries.popups.exercise-entries')
            <button ng-click="showExerciseEntryInputs = !showExerciseEntryInputs" class="btn btn-sm btn-success">Add manual entry</button>
            <div ng-show="showExerciseEntryInputs">
                @include('pages.entries.exercise-entry-inputs')
            </div>

            @include('pages.entries.exercise-entries')
        </div>

        <div class="series-exercises-container">

            <div>
                <table id="series-table" class="table table-bordered">
                    <tr>
                        <th>Series</th>
                        <th>Days ago</th>
                        <th>Priority</th>
                    </tr>
                    <tr ng-repeat="series in exercise_series | orderBy: 'lastDone' | filter:{'priority': seriesPriorityFilter}" ng-class="{'selected': series.id === selected.exercise_series.id}">
                        <td class="actions">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    [[series.name]] <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a ng-click="getExercisesInSeries(series)" href="#">Exercises</a></li>
                                    <li><a ng-click="getExerciseSeriesHistory(series)" href="#">History</a></li>
                                    <li><a ng-click="showExerciseSeriesPopup(series)" href="#">Edit</a></li>
                                </ul>
                            </div>
                        </td>

                        <td ng-click="getExercisesInSeries(series)" class="name">[[series.lastDone]]</td>
                        <td>[[series.priority]]</td>
                    </tr>
                </table>
            </div>

            @include('pages.exercises.series-exercises')
        </div>


    </div>

</div>

@include('templates.footer')

</body>
</html>