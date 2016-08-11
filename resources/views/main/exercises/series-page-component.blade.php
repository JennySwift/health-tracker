<script id="series-page-template" type="x-template">

    <div id="exercise-series">

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
                <th>Step</th>
                <th>Name</th>
                <th><span class="fa fa-exclamation"></span></th>
                <th class="big-screens">Target</th>
                <th>Last</th>
                <th>Add</th>
            </tr>
            <tbody v-for="series in exercisesBySeries">
                <tr class="series-name">
                    <td
                            colspan="6"
                    >

                        <button v-on:click="showExerciseSeriesPopup($key)" class="btn btn-sm">@{{ $key }}</button>
                        <button v-on:click="getExerciseSeriesHistory($key)" class="btn btn-sm">History</button>
                    </td>
                </tr>
                <tr
                        v-for="exercise in series | filterExercises"
                        class="hover pointer"
                >
                    <td v-on:click="showExercisePopup(exercise)">@{{ exercise.stepNumber }}</td>
                    <td v-on:click="showExercisePopup(exercise)">@{{ exercise.name }}</td>
                    <td v-on:click="showExercisePopup(exercise)">@{{ exercise.priority }}</td>
                    <td v-on:click="showExercisePopup(exercise)" class="big-screens">@{{ exercise.target }}</td>
                    <td v-on:click="showExercisePopup(exercise)">@{{ exercise.lastDone }}</td>
                    <td>
                        <button
                            v-on:click="insertExerciseSet(exercise)"
                            class="btn btn-default btn-xs">
                            <i class="fa fa-plus"></i>
                            @{{ exercise.defaultQuantity }}
                            @{{ exercise.defaultUnit.data.name }}
                        </button>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>


</script>