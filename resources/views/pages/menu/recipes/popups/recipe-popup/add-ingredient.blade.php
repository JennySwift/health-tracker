<h3>Add ingredient</h3>

<!-- I think these inputs are much the same as in entry-inputs.php. -->

<div class="margin-bottom">
    <div>
        <input
            v-model="newIngredient.food.name"
            v-on:keyup="autocompleteFood($event.keyCode);
            insertOrAutocompleteFoodEntry($event.keyCode)"
            v-on:blur="show.autocomplete_options.foods = false"
            type="text"
            placeholder="add food to @{{ selectedRecipe.name }}"
            id="recipe-popup-food-input"
            class="form-control"
        >

        {{--<div--}}
            {{--v-show="showAutocompleteOptions.foods"--}}
            {{--class="autocomplete-dropdown"--}}
        {{-->--}}
            {{--<div--}}
                {{--v-for="food in selectedRecipe.autocomplete_options"--}}
                {{--v-bind:class="{'selected': food.selected}"--}}
                {{--class="autocomplete-dropdown-item">--}}
                {{--@{{ food.name }}--}}
            {{--</div>--}}
        {{--</div>--}}

        <input
            v-model="newIngredient.quantity"
            v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"
            type="text"
            placeholder="quantity"
            id="recipe-popup-food-quantity"
            class="form-control"
        >
        <select
            v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"
            name=""
            id="recipe-popup-unit"
            class="form-control"
        >
            {{--<option--}}
                {{--v-for="unit in selected.food.units"--}}
                {{--ng-selected="unit.id === selected.food.default_unit.id"--}}
                {{--ng-value="unit.id">--}}
                {{--@{{ unit.name }}--}}
            {{--</option>--}}
        </select>

        <input
            v-model="newIngredient.description"
            v-on:keyup="insertOrAutocompleteFoodEntry($event.keyCode)"
            type="text"
            placeholder="description"
            class="form-control"
        >
    </div>
</div>