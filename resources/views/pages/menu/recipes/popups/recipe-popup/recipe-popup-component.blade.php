<script id="recipe-popup-template" type="x-template">

    <div v-show="showRecipePopup"
         v-on:click="closePopup($event, 'recipePopup')"
         class="popup-outer"
    >

        <div id="recipe-popup" class="popup-inner">

            <h1 class="center">@{{ selectedRecipe.name }}</h1>

            @include('pages.menu.recipes.popups.recipe-popup.add-ingredient')
            @include('pages.menu.recipes.popups.recipe-popup.ingredients')
            @include('pages.menu.recipes.popups.recipe-popup.steps')
            @include('pages.menu.recipes.popups.recipe-popup.tags')

        </div>

    </div>

</script>