<script id="new-menu-entry-template" type="x-template">

    <div class="margin-bottom">

        <temporary-recipe-popup
            {{--:insert-entries-for-recipe="insertEntriesForRecipe"--}}
        >
        </temporary-recipe-popup>

        <autocomplete
                :insert-item-function="insertMenuEntry"
                url="/api/menu"
                autocomplete-field="food or recipe"
                id-to-focus-after-autocomplete="new-ingredient-quantity"
                autocomplete-field-id="new-menu-entry-food"
        >
        </autocomplete>

        <div v-if="newIngredient.type === 'food'" class="form-group">
            <label for="new-ingredient-quantity">Quantity</label>
            <input
                    v-model="newIngredient.quantity"
                    v-on:keyup.13="insertMenuEntry()"
                    type="text"
                    id="new-ingredient-quantity"
                    name="new-ingredient-quantity"
                    placeholder="quantity"
                    class="form-control"
            >
        </div>

        <div v-if="newIngredient.type === 'food'" class="form-group">
            <label for="new-ingredient-unit-name">Unit</label>

            <select
                    v-model="newIngredient.unit"
                    v-on:keyup.13="insertMenuEntry()"
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

    </div>

</script>