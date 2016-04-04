<script id="recipe-popup-template" type="x-template">

    <div v-show="showPopup"
         v-on:click="closePopup($event)"
         class="popup-outer"
    >

        <div id="recipe-popup" class="popup-inner">

            <div class="content">
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

                <new-food-entry
                        :date="date"
                        :selected-recipe.sync="selectedRecipe"
                >
                </new-food-entry>

                @include('main.menu.recipes.recipe-popup.ingredients')
                @include('main.menu.recipes.recipe-popup.steps')
                @include('main.menu.recipes.recipe-popup.tags')
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Close</button>
                <button v-on:click="updateRecipe()" class="btn btn-success">Save</button>
            </div>

        </div>

    </div>

</script>