<script id="new-ingredient-template" type="x-template">

    <div class="margin-bottom">
        <div>
            <input
                v-model="newIngredient.food.name"
                v-on:keyup="respondToKeyup($event.keyCode)"
                v-on:blur="showDropdown = false"
                type="text"
                placeholder="add food to @{{ recipeName }}"
                id="recipe-popup-food-input"
                class="form-control"
            >

            <div
                v-show="showDropdown"
                class="autocomplete-dropdown"
            >
                <div
                    v-for="option in autocompleteOptions"
                    v-bind:class="{'selected': currentIndex === $index}"
                    class="autocomplete-dropdown-item">
                    @{{ option.name }}
                </div>
            </div>

            <input
                v-model="newIngredient.quantity"
                v-on:keyup.13="insertFoodIntoRecipe()"
                type="text"
                placeholder="quantity"
                id="recipe-popup-food-quantity"
                class="form-control"
            >

            <div class="form-group">
                <label for="new-ingredient-unit-name">Unit</label>

                <select
                    v-model="newIngredient.unit.name"
                    v-on:keyup.13="insertFoodIntoRecipe()"
                    id="new-ingredient-unit-name"
                    class="form-control"
                >
                    <option
                        v-for="unit in newIngredient.food.units"
                        v-bind:value="unit.id"
                    >
                        @{{ unit.name }}
                    </option>
                </select>
            </div>

            {{--<input--}}
                    {{--v-model="newIngredient.description"--}}
                    {{--v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"--}}
                    {{--type="text"--}}
                    {{--placeholder="description"--}}
                    {{--class="form-control"--}}
            {{-->--}}
        </div>
    </div>

</script>