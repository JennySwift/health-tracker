var app = angular.module('tracker');

(function () {
	app.controller('entries', function ($scope, $http, DatesFactory, EntriesFactory, AutocompleteFactory, WeightsFactory, FoodsFactory, ExerciseEntriesFactory, RecipeEntriesFactory, FoodEntriesFactory) {

		/**
		 * scope properties
		 */
		
		$scope.weight = weight;
		
		$scope.entries = {
			menu: menu_entries,
			exercise: exercise_entries
		};

		$scope.temporary_recipe_popup = {};

		$scope.units = {
			food: food_units,
			exercise: exercise_units
		};

		$scope.calories = {
			day: calories_for_the_day,
			week_avg: calories_for_the_week
		};

		$scope.show = {
			autocomplete_options: {},
			popups: {}
		};

		//selected
		$scope.selected = {
			exercise: {
				unit: {}
			},
			dropdown_item: {},
			food: {},
			unit: {},
			exercise_unit: {}
		};

		//new entry
		$scope.new_entry = {
			exercise: {
				unit: {}
			},
			exercise_unit: {},
			menu: {},
			food: {},
		};

		//autocomplete
		$scope.autocomplete_options = {
			exercises: {},
			menu_items: {},
			foods: {},
			temporary_recipe_foods: {}
		};
		
		
		$scope.edit_weight = false;

		$scope.date = {};
		
		if ($scope.date.typed === undefined) {
			$scope.date.typed = Date.parse('today').toString('dd/MM/yyyy');
		}
		$scope.date.long = Date.parse($scope.date.typed).toString('dd MMM yyyy');

		$scope.goToDate = function ($number) {
			$scope.date.typed = DatesFactory.goToDate($scope.date.typed, $number);
		};

		$scope.today = function () {
			$scope.date.typed = DatesFactory.today();
		};
		$scope.changeDate = function ($keycode) {
			if ($keycode === 13) {
				$scope.date.typed = DatesFactory.changeDate($keycode, $("#date").val());
			}
		};

		/**
		 * watches
		 */
		
		$scope.$watch('date.typed', function (newValue, oldValue) {
			$scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
			$scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
			$("#date").val(newValue);

			if (newValue === oldValue) {
				// $scope.pageLoad();
			}
			else {
				$scope.getEntries();
			}
		});

		$scope.$watch('recipe.portion', function (newValue, oldValue) {
			$($scope.temporary_recipe_popup.contents).each(function () {
				if (this.original_quantity) {
					//making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
					this.quantity = this.original_quantity * newValue;
				}
			});
		});
		
		/**
		 * select
		 */

        $("#food").val("");
        $("#weight").val("");

		/**
		 * Get all the user's entries for the current date
		 */
		$scope.getEntries = function () {
			EntriesFactory.getEntries($scope.date.sql).then(function (response) {
				$scope.weight = response.data.weight;
				$scope.entries.exercise = response.data.exercise_entries;

				$scope.entries.menu = response.data.menu_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
			});
		};

		/**
		 * Get all the the user's entries for a particular exercise with a particular unit on a particular date.
		 * @param  {[type]} $exercise_id      [description]
		 * @param  {[type]} $exercise_unit_id [description]
		 * @return {[type]}                   [description]
		 */
		$scope.getSpecificExerciseEntries = function ($exercise_id, $exercise_unit_id) {
			ExerciseEntriesFactory.getSpecificExerciseEntries($scope.date.sql, $exercise_id, $exercise_unit_id).then(function (response) {
				$scope.show.popups.exercise_entries = true;
				$scope.exercise_entries_popup = response.data;
			});
		};

		/**
		 * insert
		 */
		
		$scope.insertMenuEntry = function () {
			$scope.new_entry.food.id = $scope.selected.food.id;
			$scope.new_entry.food.name = $scope.selected.food.name;
			$scope.new_entry.food.unit_id = $("#food-unit").val();

			FoodEntriesFactory.insertMenuEntry($scope.date.sql, $scope.new_entry.food).then(function (response) {
				$scope.entries.menu = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;

				if ($scope.temporary_recipe_popup.contents) {
					$scope.temporary_recipe_popup.contents.length = 0;
				}
				$scope.loading = false;
			});
		};

		$scope.insertRecipeEntry = function () {
			RecipeEntriesFactory.insertRecipeEntry($scope.date.sql, $scope.selected.menu.id, $scope.temporary_recipe_popup.contents).then(function (response) {
				$scope.entries.menu = response.data;
				$scope.show.popups.temporary_recipe = false;
			});
		};

		$scope.insertOrUpdateWeight = function ($keycode) {
			if ($keycode === 13) {
				WeightsFactory.insertWeight($scope.date.sql).then(function (response) {
					$scope.weight = response.data;
					$scope.edit_weight = false;
					$("#weight").val("");
				});
			}
		};

		$scope.insertExerciseEntry = function () {
			$scope.new_entry.exercise.unit_id = $("#exercise-unit").val();
			ExerciseEntriesFactory.insertExerciseEntry($scope.date.sql, $scope.new_entry.exercise).then(function (response) {
				$scope.entries.exercise = response.data;
			});
		};

		$scope.insertExerciseSet = function ($exercise_id) {
			ExerciseEntriesFactory.insertExerciseSet($scope.date.sql, $exercise_id).then(function (response) {
				$scope.entries.exercise = response.data;
			});
		};

		/**
		 * update
		 */
		
		$scope.editWeight = function () {
			$scope.edit_weight = true;
			setTimeout(function () {
				$("#weight").focus();
			}, 500);
		};
		
		/**
		 * delete
		 */

		$scope.deleteFoodEntry = function ($entry_id) {
			$entry_id = $entry_id || $scope.selected.entry.id;

			FoodEntriesFactory.deleteFoodEntry($entry_id, $scope.date.sql).then(function (response) {
				$scope.entries.menu = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.show.popups.delete_food_or_recipe_entry = false;
			});
		};

		$scope.deleteRecipeEntry = function () {
			RecipeEntriesFactory.deleteRecipeEntry($scope.date.sql, $scope.selected.recipe.id).then(function (response) {
				$scope.entries.menu = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.show.popups.delete_food_or_recipe_entry = false;
			});
		};

		$scope.deleteExerciseEntry = function ($id) {
			ExerciseEntriesFactory.deleteExerciseEntry($id, $scope.date.sql, $scope.exercise_entries_popup.exercise.id, $scope.exercise_entries_popup.unit.id).then(function (response) {
				$scope.entries.exercise = response.data.entries_for_day;
				$scope.exercise_entries_popup = response.data.entries_for_popup;
			});
		};

		/**
		 * media queries
		 */
		
		enquire.register("screen and (max-width: 890px", {
			match: function () {
				$("#avg-calories-for-the-week-text").text('Avg: ');
			},
			unmatch: function () {
				$("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
			}
		});

		/**
		 * autocomplete
		 */
		
		/**
		 * autocomplete exercise
		 */
		
		$scope.autocompleteExercise = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				AutocompleteFactory.exercise().then(function (response) {
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
				AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.exercises);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.exercises);
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

		/**
		 * autocomplete menu
		 */
		
		/**
		 * As user types in the input, populate the dropdown.
		 * If user presses arrows, select the appropriate item in the dropdown.
		 * If user presses enter, that is taken care of in $scope.insertOrAutocompleteMenuEntry.
		 * @param  {[type]} $keycode [description]
		 * @return {[type]}          [description]
		 */
		$scope.autocompleteMenu = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				AutocompleteFactory.menu().then(function (response) {
					console.log(response);
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
				AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.menu_items);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.menu_items);
			}
		};

		/**
		 * For when the user presses enter from any of the relevant input fields.
		 * If enter is to complete the autocomplete, call appropriate functions.
		 * If enter is to add an entry, call the appropriate function.
		 * @param  {[type]} $keycode [description]
		 * @return {[type]}          [description]
		 */
		$scope.insertOrAutocompleteMenuEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}

			//enter is pressed
			if ($scope.show.autocomplete_options.menu_items) {
				//if enter is for the autocomplete
				$scope.finishMenuAutocomplete($scope.autocomplete_options.menu_items, $("#food-quantity"));

				if ($scope.selected.menu.type === 'recipe') {
					$scope.showTemporaryRecipePopup();
				}
			}

			// enter is to add the entry
			else {
				$scope.insertMenuEntry();
			}
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

		/**
		 * autocomplete temporary recipe food
		 */

		$scope.autocompleteTemporaryRecipeFood = function ($keycode) {
			var $typing = $("#temporary-recipe-food-input").val();

			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				AutocompleteFactory.food($typing).then(function (response) {
					$scope.autocomplete_options.temporary_recipe_foods = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.temporary_recipe_foods = true;
					//select the first item
					$scope.autocomplete_options.temporary_recipe_foods[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.temporary_recipe_foods);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.temporary_recipe_foods);
			}
		};

		$scope.finishTemporaryRecipeFoodAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.temporary_recipe_popup.food = $selected;
			$scope.selected.food = $selected;
			$scope.show.autocomplete_options.temporary_recipe_foods = false;
			$($set_focus).val("").focus();
		};

		$scope.insertOrAutocompleteTemporaryRecipeFood = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.temporary_recipe_foods) {
				//enter is for the autocomplete
				$scope.finishTemporaryRecipeFoodAutocomplete($scope.autocomplete_options.temporary_recipe_foods, $("#temporary-recipe-popup-food-quantity"));
				// $scope.displayAssocUnitOptions();
			}
			else {
				// if enter is to add the entry
				$scope.insertFoodIntoTemporaryRecipe();
			}
		};

		$scope.insertFoodIntoTemporaryRecipe = function () {
			//we are adding a food to a temporary recipe
			var $unit_name = $("#temporary-recipe-popup-unit option:selected").text();
			$scope.temporary_recipe_popup.contents.push({
				"food_id": $scope.temporary_recipe_popup.food.id,
				"name": $scope.temporary_recipe_popup.food.name,
				"quantity": $scope.temporary_recipe_popup.quantity,
				"unit_id": $("#temporary-recipe-popup-unit").val(),
				"unit_name": $unit_name,
				"units": $scope.temporary_recipe_popup.food.units
			});
			
			$("#temporary-recipe-food-input").val("").focus();
		};

		/**
		 * other
		 */
		
		$scope.closePopup = function ($event, $popup) {
			var $target = $event.target;
			if ($target.className === 'popup-outer') {
				$scope.show.popups[$popup] = false;
			}
		};

		$scope.deleteFromTemporaryRecipe = function ($item) {
			$scope.temporary_recipe_popup.contents = _.without($scope.temporary_recipe_popup.contents, $item);
		};

		$scope.showTemporaryRecipePopup = function () {
			$scope.show.popups.temporary_recipe = true;
			FoodsFactory.getRecipeContents($scope.selected.menu.id).then(function (response) {
				$scope.temporary_recipe_popup = response.data;

				$($scope.temporary_recipe_popup.contents).each(function () {
					this.original_quantity = this.quantity;
				});
			});
		};

		$scope.showDeleteFoodOrRecipeEntryPopup = function ($entry_id, $recipe_id) {
			$scope.show.popups.delete_food_or_recipe_entry = true;
			$scope.selected.entry = {
				id: $entry_id
			};
			$scope.selected.recipe = {
				id: $recipe_id
			};
		};

	}); //end controller

})();