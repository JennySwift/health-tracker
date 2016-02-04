<script id="new-exercise-entry-template" type="x-template">

    <new-item-with-autocomplete
            {{--:selected-recipe.sync="selectedRecipe"--}}
            :insert-item-function="insertExerciseEntry"
            url="/api/exercises"
            autocomplete-field="exercise"
    >
    </new-item-with-autocomplete>

</script>