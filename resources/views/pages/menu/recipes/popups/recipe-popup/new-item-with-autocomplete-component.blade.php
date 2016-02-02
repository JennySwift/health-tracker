<script id="new-item-with-autocomplete-template" type="x-template">

    <div class="margin-bottom">
        <div>

            <div class="form-group">
                <label for="new-ingredient-food-name">@{{ autocompleteField | capitalize }}</label>
                <input
                    v-model="newIngredient[autocompleteField].name"
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
                    v-on:keyup.13="insertItem()"
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
                    v-on:keyup.13="insertItem()"
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

            <div v-if="showDescriptionField" class="form-group">
                <label for="new-ingredient-description">Description</label>
                <input
                    v-model="newIngredient.description"
                    v-on:keyup.13="insertItem()"
                    type="text"
                    id="new-ingredient-description"
                    name="new-ingredient-description"
                    placeholder="description"
                    class="form-control"
                >
            </div>
        </div>
    </div>











    {{--<div class="entry-inputs">--}}
        {{--<div>--}}
            {{--<input--}}
                    {{--v-model="newEntry.menu.name"--}}
                    {{--v-on:keyup="autocompleteMenu($event.keyCode); insertOrAutocompleteMenuEntry($event.keyCode)"--}}
                    {{--v-on:blur="autocomplete_options.menu_items = ''"--}}
                    {{--type="text"--}}
                    {{--placeholder="food"--}}
                    {{--id="menu"--}}
                    {{--class="form-control"--}}
            {{-->--}}

            {{--<div v-show="showAutocompleteOptions.menu_items">--}}
                {{--<div--}}
                        {{--v-repeat="item in autocomplete_options.menu_items"--}}
                        {{--v-class="{'selected': item.selected}"--}}
                        {{--data-id="[[item.id]]"--}}
                        {{--data-type="[[item.type]]"--}}
                        {{--class="autocomplete-dropdown-item"--}}
                {{-->--}}
                    {{--[[item.name]]--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div>--}}
                {{--<input--}}
                        {{--v-model="new_entry.food.quantity"--}}
                        {{--v-on:keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')"--}}
                        {{--type="text"--}}
                        {{--placeholder="quantity"--}}
                        {{--id="food-quantity"--}}
                        {{--class="form-control"--}}
                {{-->--}}

                {{--<select--}}
                        {{--v-on:keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')"--}}
                        {{--name=""--}}
                        {{--id="food-unit"--}}
                        {{--class="form-control"--}}
                {{-->--}}
                    {{--<option--}}
                            {{--v-for="unit in selected.food.units"--}}
                            {{--v-selected="unit.id === selected.food.default_unit.id"--}}
                            {{--value="[[unit.id]]"--}}
                    {{-->--}}
                        {{--[[unit.name]]--}}
                    {{--</option>--}}
                {{--</select>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

</script>