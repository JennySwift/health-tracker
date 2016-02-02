<div class="top-buttons">

    <div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a v-on:click="showNewSeriesFields = true;showNewExerciseFields = false" href="#">Series</a></li>
                <li><a v-on:click="showNewExerciseFields = true;showNewSeriesFields = false" href="#">Exercise</a></li>
            </ul>
        </div>
    </div>

    @include('pages.exercises.new-series')
    @include('pages.exercises.new-exercise')

    <div class="filters">
        <input v-model="seriesPriorityFilter" type="text" placeholder="filter by priority">
    </div>

</div>

{{--This div used to be exercise entries controller--}}
{{--<div>--}}
    {{--@include('pages.entries.popups.specific-exercise-entries-popup')--}}
    {{--<button v-on:click="showExerciseEntryInputs = !showExerciseEntryInputs" class="btn btn-sm btn-success">Add manual entry</button>--}}
    {{--<div v-show="showExerciseEntryInputs">--}}
        {{--@include('pages.entries.exercise-entry-inputs')--}}
    {{--</div>--}}

    {{--@include('pages.entries.exercise-entries')--}}
{{--</div>--}}
