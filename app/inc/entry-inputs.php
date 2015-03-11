<!-- food entry -->
<div class="col-xs-6 col-sm-4 margin-bottom">
	<div>	
		<input ng-model="new_entry.menu.name" ng-keyup="autocompleteMenu($event.keyCode); insertOrAutocompleteMenuEntry($event.keyCode, 'menu')" ng-blur="autocomplete.menu = ''" type="text" placeholder="food" id="food" class="form-control">
		
		<div ng-show="show.autocomplete.new_menu_entry">
			<div ng-repeat="item in autocomplete.menu" ng-class="{'selected': item.selected}" data-id="{{item.id}}" data-type="{{item.type}}" class="autocomplete-dropdown-item">{{item.name}}</div>
		</div>

		<div>
			<input ng-model="new_entry.food.quantity" ng-keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')" type="text" placeholder="quantity" id="food-quantity" class="form-control">
			<select ng-keyup="insertOrAutocompleteMenuEntry($event.keyCode, 'menu')" name="" id="food-unit" class="form-control">
				<option ng-repeat="unit in selected.food.assoc_units" ng-selected="unit.default_unit === true" value="{{unit.id}}">{{unit.name}}</option>
			</select>
		</div>
	</div>
</div>

<!-- exercise entry -->
<div class="col-xs-6 col-sm-4 margin-bottom">
	<div>
		<!-- <input ng-model="new_entry.exercise.name" ng-keyup="autocomplete({keycode:$event.keyCode, autocomplete_property:'exercise', show_property:'new_exercise_entry', function_property:'autocompleteExercise', input_to_fill: new_entry.exercise.name}); enter($event.keyCode, 'exercise')" type="text" placeholder="exercise" id="exercise" class="form-control"> -->
		<input ng-model="new_entry.exercise.name" ng-keyup="autocompleteExercise($event.keyCode); insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" type="text" placeholder="exercise" id="exercise" class="form-control">

		<div ng-show="show.autocomplete.new_exercise_entry">
			<div ng-repeat="item in autocomplete.exercise" ng-class="{'selected': item.selected}" class="autocomplete-dropdown-item">{{item.name}}</div>
		</div>

		<input ng-model="new_entry.exercise.quantity" ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" type="text" id="exercise-quantity" placeholder="quantity" class="form-control">

		<select ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" class="form-control">
			<option ng-repeat="unit in units.exercise" ng-selected="unit.id === selected.exercise.default_unit_id" value="{{unit.id}}" id="exercise-unit">{{unit.name}}</option>
		</select>
	</div>
</div>