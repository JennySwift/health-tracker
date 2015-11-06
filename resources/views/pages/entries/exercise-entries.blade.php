<div>
    <table class="table table-bordered">
        <caption>exercise entries</caption>
        <tr>
            <th>exercise</th>
            <th>description</th>
            <th>sets</th>
            <th>total</th>
            <th></th>
        </tr>

        <tr ng-repeat="entry in exerciseEntries">
            <td ng-click="getSpecificExerciseEntries(entry)" class="pointer">[[entry.exercise.name]]</td>
            <td ng-click="getSpecificExerciseEntries(entry)" class="pointer">[[entry.exercise.description]]</td>
            <td ng-click="getSpecificExerciseEntries(entry)" class="pointer">[[entry.sets]]</td>
            <td ng-click="getSpecificExerciseEntries(entry)" class="pointer">[[entry.total]] [[entry.unit.name]]</td>
            <td>
                <button
                    ng-if="entry.unit.id === entry.default_unit_id"
                    ng-click="insertExerciseSet(entry.exercise)"
                    class="btn-xs">
                    add set
                </button>
            </td>
        </tr>
    </table>
</div>