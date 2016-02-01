<script id="menu-entries-template" type="x-template">

<div>
    <new-item-with-autocomplete
            {{--:selected-recipe.sync="selectedRecipe"--}}
            :insert-item-function="insertMenuEntry"
    >
    </new-item-with-autocomplete>

    @include('pages.entries.menu-entries')
</div>

</script>