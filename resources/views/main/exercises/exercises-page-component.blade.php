<script id="exercises-page-template" type="x-template">

    <div id="exercise-series">

        @include('main.exercises.exercise-filters')

        <series-history-popup
                :exercise-series-history="exerciseSeriesHistory"
                :selected-series="selectedSeries"
        >
        </series-history-popup>

        <series-popup
                :selected-series="selectedSeries"
                :exercise-series.sync="exerciseSeries"
        >
        </series-popup>

        <div>
            @include('main.exercises.series-top-row')
        </div>

        <exercise-popup
                :selected-exercise="selectedExercise"
                :exercises.sync="shared.exercises"
                :exercise-series="exerciseSeries"
                :programs="programs"
                :units="units"
        >
        </exercise-popup>


        <table class="table table-bordered">
            <tr>
                <th class="big-screens">Step</th>
                <th>Name</th>
                <th class="big-screens"><span class="fa fa-exclamation"></span></th>
                <th class="big-screens">Target</th>
                <th>Last</th>
                <th>Add</th>
            </tr>
            <tr
                    v-for="exercise in shared.exercises | filterExercises"
                    v-bind:class="{'stretch': exercise.stretch}"
                    class="hover pointer"
            >
                <td v-on:click="showExercisePopup(exercise)" class="big-screens">@{{ exercise.stepNumber }}</td>
                <td v-on:click="showExercisePopup(exercise)">@{{ exercise.name }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="big-screens">@{{ exercise.priority }}</td>
                <td v-on:click="showExercisePopup(exercise)" class="big-screens">@{{ exercise.target }}</td>
                <td v-on:click="showExercisePopup(exercise)">@{{ exercise.lastDone }}</td>
                <td>
                    <button
                        v-on:click="insertExerciseSet(exercise)"
                        class="btn btn-default btn-xs"
                    >
                        <i class="fa fa-plus"></i>
                        @{{ exercise.defaultQuantity }}
                        @{{ exercise.defaultUnit.data.name }}
                    </button>
                </td>
            </tr>
        </table>

    </div>


</script>