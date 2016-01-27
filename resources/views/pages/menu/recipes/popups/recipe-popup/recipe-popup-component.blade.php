<script id="recipe-popup-template" type="x-template">

    <div v-show="showRecipePopup"
         v-on:click="closePopup($event, 'recipePopup')"
         class="popup-outer"
    >

        <div id="recipe-popup" class="popup-inner">

            <div class="form-group">
                <label for="selected-recipe-name">Name</label>
                <input
                    v-model="selectedRecipe.name"
                    type="text"
                    id="selected-recipe-name"
                    name="selected-recipe-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            @include('pages.menu.recipes.popups.recipe-popup.add-ingredient')
            @include('pages.menu.recipes.popups.recipe-popup.ingredients')
            @include('pages.menu.recipes.popups.recipe-popup.steps')
            @include('pages.menu.recipes.popups.recipe-popup.tags')

        </div>

    </div>

</script>