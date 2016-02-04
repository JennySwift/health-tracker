<script id="new-food-entry-template" type="x-template">

    <div class="margin-bottom">

        <autocomplete
                :insert-item-function="addIngredientToRecipe"
                url="/api/foods"
                autocomplete-field="food"
                id-to-focus-after-autocomplete="new-ingredient-quantity"
                autocomplete-field-id="new-menu-entry-food"
        >
        </autocomplete>

        <div class="form-group">
            <label for="new-ingredient-quantity">Quantity</label>
            <input
                    v-model="newIngredient.quantity"
                    v-on:keyup.13="addIngredientToRecipe()"
                    type="text"
                    id="new-ingredient-quantity"
                    name="new-ingredient-quantity"
                    placeholder="quantity"
                    class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-ingredient-unit-name">Unit</label>

            <select
                    v-model="newIngredient.unit"
                    v-on:keyup.13="addIngredientToRecipe()"
                    id="new-ingredient-unit-name"
                    class="form-control"
            >
                <option
                        v-for="unit in newIngredient.food.units.data"
                        v-bind:value="unit"
                >
                    @{{ unit.name }}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="new-ingredient-description">Description</label>
            <input
                    v-model="newIngredient.description"
                    v-on:keyup.13="addIngredientToRecipe()"
                    type="text"
                    id="new-ingredient-description"
                    name="new-ingredient-description"
                    placeholder="description"
                    class="form-control"
            >
        </div>
    </div>

</script>