<script id="new-ingredient-template" type="x-template">

    <div class="margin-bottom">
        <div>
            <input
                v-model="newIngredient.food.name"
                v-on:keyup="respondToKeyup($event.keyCode)"
                v-on:blur="show.autocomplete_options.foods = false"
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
                    {{--v-bind:class="{'selected': option.selected}"--}}
                    v-bind:class="{'selected': currentIndex === $index}"
                    class="autocomplete-dropdown-item">
                    @{{ option.name }}
                </div>
            </div>

            {{--<input--}}
                    {{--v-model="newIngredient.quantity"--}}
                    {{--v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"--}}
                    {{--type="text"--}}
                    {{--placeholder="quantity"--}}
                    {{--id="recipe-popup-food-quantity"--}}
                    {{--class="form-control"--}}
            {{-->--}}
            {{--<select--}}
                    {{--v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"--}}
                    {{--name=""--}}
                    {{--id="recipe-popup-unit"--}}
                    {{--class="form-control"--}}
            {{-->--}}
                {{--<option--}}
                {{--v-for="unit in selected.food.units"--}}
                {{--ng-selected="unit.id === selected.food.default_unit.id"--}}
                {{--ng-value="unit.id">--}}
                {{--@{{ unit.name }}--}}
                {{--</option>--}}
            {{--</select>--}}

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