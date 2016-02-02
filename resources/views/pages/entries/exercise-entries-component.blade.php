<script id="exercise-entries-template" type="x-template">

<div>
    @include('pages.entries.popups.specific-exercise-entries-popup')

    <new-item-with-autocomplete
            {{--:selected-recipe.sync="selectedRecipe"--}}
            :insert-item-function="insertExerciseEntry"
            url="/api/exercises"
            autocomplete-field="exercise"
    >
    </new-item-with-autocomplete>

    @include('pages.entries.exercise-entries')
</div>

</script>