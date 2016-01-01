<div>
    <table id="exercises-table" class="table">
        <tr>
            <th>Step</th>
            <th>Name</th>
            <th>Description</th>
            <th>Priority</th>
            <th>Default</th>
            <th>Target</th>
            <th>Program</th>
            <th>Days ago</th>
            <th>Add</th>
        </tr>
        <tr ng-repeat="exercise in selected.exercise_series.exercises.data | filter:{'priority': seriesPriorityFilter}">
            <td ng-click="showExercisePopup(exercise)">[[exercise.stepNumber]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.name]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.description]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.priority]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.defaultQuantity]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.target]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.program.name]]</td>
            <td ng-click="showExercisePopup(exercise)">[[exercise.lastDone]]</td>
            <td>
                <button ng-click="insertExerciseSet(exercise)" class="btn btn-default btn-xs">Add set</button>
            </td>
        </tr>
    </table>

    <p ng-if="selected.exercise_series.exercises.data.length === 0">No exercises in the series</p>

</div>