var app = angular.module('tracker');

(function () {
	app.controller('autocomplete', function ($scope, $http, date, select, autocomplete, quickRecipe, foods, exercises, journal, tags, units, weights) {

		/**
		 * select
		 */
		
		$scope.autocompleteExercise = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				select.autocompleteExercise().then(function (response) {
					//fill the dropdown
					$scope.autocomplete_options.exercises = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.exercises = true;
					//select the first item
					$scope.autocomplete_options.exercises[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete_options.exercises);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete_options.exercises);
			}
		};

		$scope.autocompleteMenu = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				select.autocompleteMenu().then(function (response) {
					//fill the dropdown
					$scope.autocomplete_options.menu_items = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.menu_items = true;
					//select the first item
					$scope.autocomplete_options.menu_items[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete_options.menu_items);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete_options.menu_items);
			}
		};

		$scope.autocompleteFood = function ($keycode) {
			var $typing = $("#recipe-popup-food-input").val();
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				select.autocompleteFood($typing).then(function (response) {
					$scope.autocomplete_options.foods = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.foods = true;
					//select the first item
					$scope.autocomplete_options.foods[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete_options.foods);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete_options.foods);
			}
		};

		$scope.autocompleteTemporaryRecipeFood = function ($keycode) {
			var $typing = $("#temporary-recipe-food-input").val();

			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				select.autocompleteFood($typing).then(function (response) {
					$scope.autocomplete_options.temporary_recipe_foods = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.temporary_recipe_foods = true;
					//select the first item
					$scope.autocomplete_options.temporary_recipe_foods[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete_options.temporary_recipe_foods);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete_options.temporary_recipe_foods);
			}
		};

		$scope.finishExerciseAutocomplete = function ($array, $selected) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			$selected = $selected || _.findWhere($array, {selected: true});
			$scope.selected.exercise = $selected;
			$scope.selected.exercise_unit.id = $scope.selected.exercise.default_exercise_unit_id;
			$scope.new_entry.exercise = $selected;
			$scope.new_entry.exercise.quantity = $scope.selected.exercise.default_quantity;
			$scope.selected.exercise = $selected;
			$scope.show.autocomplete_options.exercises = false;
			setTimeout(function () {
				$("#exercise-quantity").focus().select();
			}, 500);
		};

		$scope.finishMenuAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.selected.food = $selected;
			$scope.new_entry.menu = $selected;
			$scope.selected.menu = $selected;
			$scope.show.autocomplete_options.menu_items = false;
			$($set_focus).val("").focus();
		};

		$scope.finishFoodAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.recipe_popup.food = $selected;
			$scope.selected.food = $selected;
			$scope.show.autocomplete_options.foods = false;
			$($set_focus).val("").focus();
		};

		$scope.finishTemporaryRecipeFoodAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.temporary_recipe_popup.food = $selected;
			$scope.selected.food = $selected;
			$scope.show.autocomplete_options.temporary_recipe_foods = false;
			$($set_focus).val("").focus();
		};

		$scope.insertOrAutocompleteExerciseEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.exercises) {
				//if enter is for the autocomplete
				$scope.finishExerciseAutocomplete($scope.autocomplete_options.exercises);
			}
			else {
				// if enter is to add the entry
				$scope.insertExerciseEntry();
				console.log('something');
			}
		};

		$scope.insertOrAutocompleteMenuEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.menu_items) {
				//if enter is for the autocomplete
				$scope.finishMenuAutocomplete($scope.autocomplete_options.menu_items, $("#food-quantity"));
				$scope.displayAssocUnitOptions();

				if ($scope.selected.menu.type === 'recipe') {
					$scope.showTemporaryRecipePopup();
				}
			}
			else {
				// if enter is to add the entry
				$scope.insertMenuEntry();
			}
		};

		$scope.insertOrAutocompleteFoodEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.foods) {
				//enter is for the autocomplete
				$scope.finishFoodAutocomplete($scope.autocomplete_options.foods, $("#recipe-popup-food-quantity"));
				$scope.displayAssocUnitOptions();
			}
			else {
				// if enter is to add the entry
				$scope.insertFoodIntoRecipe();
			}
		};

		$scope.insertOrAutocompleteTemporaryRecipeFood = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.temporary_recipe_foods) {
				//enter is for the autocomplete
				$scope.finishTemporaryRecipeFoodAutocomplete($scope.autocomplete_options.temporary_recipe_foods, $("#temporary-recipe-popup-food-quantity"));
				$scope.displayAssocUnitOptions();
			}
			else {
				// if enter is to add the entry
				$scope.insertFoodIntoTemporaryRecipe();
			}
		};

		$scope.insertFoodIntoRecipe = function () {
			//we are adding a food to a permanent recipe
			var $data = {
				recipe_id: $scope.selected.recipe.id,
				food_id: $scope.selected.food.id,
				unit_id: $scope.selected.unit.id,
				quantity: $scope.recipe_popup.food.quantity,
				description: $scope.recipe_popup.food.description
			};

			insert.foodIntoRecipe($data).then(function (response) {
				$scope.recipe.contents = response.data;
			});
			$("#recipe-popup-food-input").val("").focus();
			$scope.recipe_popup.food.description = "";
		};

		$scope.insertFoodIntoTemporaryRecipe = function () {
			//we are adding a food to a temporary recipe
			var $unit_name = $("#temporary-recipe-popup-unit-select option:selected").text();
			$scope.recipe.temporary_contents.push({
				"food_id": $scope.food.id,
				"food_name": $scope.food.name,
				"quantity": $scope.food.quantity,
				"unit_id": $scope.unit_id,
				"unit_name": $unit_name,
				"assoc_units": $scope.food.assoc_units
			});
			
			$("#temporary-recipe-food-input").val("").focus();
		};

		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
	}); //end controller

})();