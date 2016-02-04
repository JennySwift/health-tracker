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

            <h3>Add ingredient</h3>
            <new-item-with-autocomplete
                :selected-recipe.sync="selectedRecipe"
                :insert-item-function="addIngredientToRecipe"
                show-description-field="true"
                url="/api/foods"
                autocomplete-field="food"
            >
            </new-item-with-autocomplete>
            @include('main.menu.recipes.recipe-popup.ingredients')
            @include('main.menu.recipes.recipe-popup.steps')
            @include('main.menu.recipes.recipe-popup.tags')

        </div>

    </div>

</script>