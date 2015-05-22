<!-- menu entry -->
<div>
	<div>	
		<input ng-model="new_entry.menu.name" ng-keyup="autocompleteMenu($event.keyCode); insertOrAutocompleteMenuEntry($event.keyCode)" ng-blur="autocomplete_options.menu_items = ''" type="text" placeholder="food" id="menu" class="form-control">
		
		<div ng-show="show.autocomplete_options.menu_items">
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

<!-- exercise entry -->
<div class="margin-bottom">
	<div>
		<!-- <input ng-model="new_entry.exercise.name" ng-keyup="autocomplete({keycode:$event.keyCode, autocomplete_property:'exercise', show_property:'new_exercise_entry', function_property:'autocompleteExercise', input_to_fill: new_entry.exercise.name}); enter($event.keyCode, 'exercise')" type="text" placeholder="exercise" id="exercise" class="form-control"> -->
		<input ng-model="new_entry.exercise.name" ng-keyup="autocompleteExercise($event.keyCode); insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" ng-blur="show.autocomplete_options.exercises = false" type="text" placeholder="exercise" id="exercise" class="form-control">

		<div ng-show="show.autocomplete_options.exercises">
			<div ng-repeat="item in autocomplete_options.exercises" ng-class="{'selected': item.selected}" ng-mousedown="finishExerciseAutocomplete($scope.autocomplete_options.exercises, item)" class="autocomplete-dropdown-item pointer">[[item.name]] ([[item.description]])</div>
		</div>

		<input ng-model="new_entry.exercise.quantity" ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" type="text" id="exercise-quantity" placeholder="quantity" class="form-control">

		<select ng-model="selected.exercise_unit.id" ng-keyup="insertOrAutocompleteExerciseEntry($event.keyCode, 'exercise')" class="form-control">
			<option ng-repeat="unit in units.exercise" ng-selected="unit.id === selected.exercise.default_unit_id" value="[[unit.id]]" id="exercise-unit">[[unit.name]]</option>
		</select>
	</div>
</div>