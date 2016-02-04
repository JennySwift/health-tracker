<div class="top-buttons">

    <div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a
                        v-on:click="showNewSeriesFields = true;showNewExerciseFields = false"
                    >
                        Series
                    </a>
                </li>
                <li>
                    <a
                        v-on:click="showNewExerciseFields = true;showNewSeriesFields = false"
                    >
                        Exercise
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <new-series
        :show-new-series-fields="showNewSeriesFields"
        :exercise-series.sync="exerciseSeries"
    >
    </new-series>

    <new-exercise
        :show-new-exercise-fields.sync="showNewExerciseFields"
        :programs="programs"
        :exercise-series="exerciseSeries"
        :units="units"
    >
    </new-exercise>

    <div class="filters">
        <input v-model="priorityFilter" type="text" placeholder="filter by priority">
    </div>

</div>

<div>
    <button v-on:click="showExerciseEntryInputs = !showExerciseEntryInputs" class="btn btn-sm btn-success">Add manual entry</button>
    <div v-show="showExerciseEntryInputs">
        <new-item-with-autocomplete
                {{--:selected-recipe.sync="selectedRecipe"--}}
                :insert-item-function="insertExerciseEntry"
                url="/api/exercises"
                autocomplete-field="exercise"
        >
        </new-item-with-autocomplete>
    </div>

    <exercise-entries
        :date="date"
    >
    </exercise-entries>
</div>
