<div>
    
    <table id="exercises-table" class="table">
        <tr>
            <th>Step</th>
            <th>Name</th>
            <th>Priority</th>
            <th>Target</th>
            <th>Days ago</th>
            <th>Add</th>
        </tr>
        <tr v-for="exercise in selectedSeries.exercises.data | filter:{'priority': seriesPriorityFilter}">
            <td v-on:click="showExercisePopup(exercise)">@{{ exercise.stepNumber }}</td>
            <td v-on:click="showExercisePopup(exercise)">@{{ exercise.name }}</td>
            <td v-on:click="showExercisePopup(exercise)">@{{ exercise.priority }}</td>
            <td v-on:click="showExercisePopup(exercise)">@{{ exercise.target }}</td>
            <td v-on:click="showExercisePopup(exercise)">@{{ exercise.lastDone }}</td>
            <td>
                <button v-on:click="insertExerciseSet(exercise)" class="btn btn-default btn-xs"><i class="fa fa-plus"></i> @{{ exercise.defaultQuantity }} @{{ exercise.defaultUnit.name }}</button>
            </td>
        </tr>
    </table>

    <p v-if="selectedSeries.exercises.data.length === 0">No exercises in the series</p>

</div>