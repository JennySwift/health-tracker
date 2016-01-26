<div class="top-buttons">

    <div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a ng-click="showNewSeriesFields = true;showNewExerciseFields = false" href="#">Series</a></li>
                <li><a ng-click="showNewExerciseFields = true;showNewSeriesFields = false" href="#">Exercise</a></li>
            </ul>
        </div>
    </div>

    @include('pages.exercises.new-series')
    @include('pages.exercises.new-exercise')

    <div class="filters">
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
