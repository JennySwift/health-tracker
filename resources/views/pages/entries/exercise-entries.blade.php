<div ng-controller="ExerciseEntriesController">
    <table class="table table-bordered">
        <caption>exercise entries</caption>
        <tr>
            <th>exercise</th>
            <th>description</th>
            <th>sets</th>
            <th>total</th>
            <th></th>
        </tr>

        <tr ng-repeat="entry in entries.exercise" data-entry-id="[[entry.entry_id]]">
            <td ng-click="getSpecificExerciseEntries(entry.exercise_id, entry.unit_id)" class="pointer">[[entry.name]]</td>
            <td ng-click="getSpecificExerciseEntries(entry.exercise_id, entry.unit_id)" class="pointer">[[entry.description]]</td>
            <td ng-click="getSpecificExerciseEntries(entry.exercise_id, entry.unit_id)" class="pointer">[[entry.sets]]</td>
            <td ng-click="getSpecificExerciseEntries(entry.exercise_id, entry.unit_id)" class="pointer">[[entry.total]] [[entry.unit_name]]</td>
            <td><button ng-if="entry.unit_id === entry.default_unit_id" ng-click="insertExerciseSet(entry.exercise_id)" class="btn-xs">add set</button></td>
        </tr>
    </table>
</div>