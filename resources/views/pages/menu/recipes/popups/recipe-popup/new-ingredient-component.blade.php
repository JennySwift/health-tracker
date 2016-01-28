<script id="new-ingredient-template" type="x-template">

    <div class="margin-bottom">
        <div>

            <div class="form-group">
                <label for="new-ingredient-food-name">Food</label>
                <input
                    v-model="newIngredient.food.name"
                    v-on:keyup="respondToKeyup($event.keyCode)"
                    v-on:blur="showDropdown = false"
                    type="text"
                    id="new-ingredient-food-name"
                    name="new-ingredient-food-name"
                    placeholder="food"
                    class="form-control"
                >
            </div>

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

            <div class="form-group">
                <label for="new-ingredient-quantity">Quantity</label>
                <input
                    v-model="newIngredient.quantity"
                    v-on:keyup.13="insertFoodIntoRecipe()"
                    type="text"
                    id="new-ingredient-quantity"
                    name="new-ingredient-quantity"
                    placeholder="quantity"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="new-ingredient-unit-name">Unit</label>

                {{--<pre v-if="newIngredient.food.units">@{{$data.newIngredient.food.units.data[1] | json}}</pre>--}}
                {{--<pre>@{{$data.newIngredient.food.defaultUnit.data | json}}</pre>--}}
                {{--<pre>@{{$data.newIngredient.unit | json}}</pre>--}}

                <select
                    v-model="newIngredient.unit"
                    v-on:keyup.13="insertFoodIntoRecipe()"
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
                    v-on:keyup.13="insertFoodIntoRecipe()"
                    type="text"
                    id="new-ingredient-description"
                    name="new-ingredient-description"
                    placeholder="description"
                    class="form-control"
                >
            </div>
        </div>
    </div>

</script>