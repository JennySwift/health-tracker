<script id="exercises-page-template" type="x-template">

<div id="exercises">
    
    <exercise-popup
            :selected-exercise="selectedExercise"
    >
    </exercise-popup>

    <div>

        @include('pages.exercises.exercise-filters')

        <new-exercise
                :show-new-exercise-fields="showNewExerciseFields"
                :exercises.sync="exercises"
        >
        </new-exercise>

        <table class="table table-bordered">
            <tr>
                <th>name</th>
                <th>description</th>
                <th>step</th>
                <th>series</th>
                <th>default quantity</th>
                <th>default unit</th>
                <th>target</th>
                <th>priority</th>
                <th>program</th>
                <th>x</th>
            </tr>
            <tr v-for="exercise in exercises | filter:{name: filter.exercises.name, description: filter.exercises.description, priority: filter.exercises.priority, series: {name: filter.exercises.series}}" class="hover">
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.name }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.description }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.stepNumber }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.series.name }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.defaultQuantity }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.defaultUnit.name }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.target }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.priority }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="pointer">@{{ exercise.program.name }}</td>
                <td><i v-on:click="deleteExercise(exercise)" class="delete-item fa fa-times"></i></td>
            </tr>
        </table>
    </div>

</div>

</script>