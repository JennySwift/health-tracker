<h3>Add ingredient</h3>

<!-- I think these inputs are much the same as in entry-inputs.php. -->

<div class="margin-bottom">
    <div>
        <input ng-model="recipe_popup.food.name" ng-keyup="autocompleteFood($event.keyCode); insertOrAutocompleteFoodEntry($event.keyCode)" ng-blur="show.autocomplete_options.foods = false" type="text" placeholder="add food to [[recipe_popup.recipe.name]]" id="recipe-popup-food-input" class="form-control">

        <div ng-show="showAutocompleteOptions.foods" class="autocomplete-dropdown">
            <div ng-repeat="food in recipe_popup.autocomplete_options" ng-class="{'selected': food.selected}" class="autocomplete-dropdown-item">[[food.name]]</div>
        </div>

        <input ng-model="recipe_popup.food.quantity" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="quantity" id="recipe-popup-food-quantity" class="form-control">
        <select ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" name="" id="recipe-popup-unit" class="form-control">
            <option ng-repeat="unit in selected.food.units" ng-selected="unit.id === selected.food.default_unit.id" ng-value="unit.id">[[unit.name]]</option>
        </select>

        <input ng-model="recipe_popup.food.description" ng-keyup="insertOrAutocompleteFoodEntry($event.keyCode)" type="text" placeholder="description" class="form-control">
    </div>
</div>