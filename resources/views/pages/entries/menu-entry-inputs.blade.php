<div class="entry-inputs">
    <div>
        <input ng-model="new_entry.menu.name" ng-keyup="autocompleteMenu($event.keyCode); insertOrAutocompleteMenuEntry($event.keyCode)" ng-blur="autocomplete_options.menu_items = ''" type="text" placeholder="food" id="menu" class="form-control">

        <div ng-show="showAutocompleteOptions.menu_items">
            <div ng-repeat="item in autocomplete_options.menu_items" ng-class="{'selected': item.selected}" data-id="[[item.id]]" data-type="[[item.type]]" class="autocomplete-dropdown-item">[[item.name]]</div>
        </div>

        <div>
            <input ng-model="new_entry.food.quantity" ng-keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')" type="text" placeholder="quantity" id="food-quantity" class="form-control">
            <select ng-keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')" name="" id="food-unit" class="form-control">
                <option ng-repeat="unit in selected.food.units" ng-selected="unit.id === selected.food.default_unit.id" value="[[unit.id]]">[[unit.name]]</option>
            </select>
        </div>
    </div>
</div>