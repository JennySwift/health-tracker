<script id="new-exercise-entry-template" type="x-template">

    <autocomplete
            {{--:selected-recipe.sync="selectedRecipe"--}}
            :insert-item-function="insertExerciseEntry"
            url="/api/exercises"
            autocomplete-field="exercise"
    >
    </autocomplete>

</script>