<script id="entries-page-template" type="x-template">

<div>

    @include('pages.entries.index')

    @include('templates.shared.date-navigation')

    <div id="info-entries-wrapper">
        @include('pages.entries.info')
    </div>

    <div id="entries">
        <new-item-with-autocomplete
                :selected-recipe.sync="selectedRecipe"
        >
        </new-item-with-autocomplete>

        <exercise-entries></exercise-entries>
    </div>

</div>

</script>