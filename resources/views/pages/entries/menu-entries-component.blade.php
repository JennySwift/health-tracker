<script id="menu-entries-template" type="x-template">

<div>
    <new-item-with-autocomplete
            {{--:selected-recipe.sync="selectedRecipe"--}}
            :insert-item-function="insertMenuEntry"
            url="/api/foods"
            autocomplete-field="food"
    >
    </new-item-with-autocomplete>

    @include('pages.entries.menu-entries')
</div>

</script>