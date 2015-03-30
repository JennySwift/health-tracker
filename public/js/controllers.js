var $page = window.location.pathname;
var app = angular.module('foodApp', ['ngSanitize', 'checklist-model']);

(function () {
	app.controller('display', function ($scope, $http, date, select, autocomplete, insert, deleteItem, update, quickRecipe) {

		// ========================================================================
		// ========================================================================
		// ============================$scope properties===========================
		// ========================================================================
		// ========================================================================

		//test
		$scope.property = {
			
		};
		//=============tabs=============
		$scope.tab = {
			foods: true
		};

		//show
		$scope.show = {
			food_info: false,
			default_exercise_unit_popup: false,
			autocomplete: {
				new_exercise_entry: false,
				new_menu_entry: false,
				new_food_entry: false
			},
			popups: {
				recipe: false,
				similar_names: false
			}
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

		//quick recipe
		$scope.quick_recipe = {};

		//new entry
		$scope.new_entry = {
			exercise: {
				unit: {}
			},
			exercise_unit: {},
			menu: {},
			food: {},
		};

		//new item-eg new food, as opposed to to food entry
		$scope.new_item = {
			recipe: {}
		};
		//journal
		$scope.journal_entry = {};
		// exercise
		$scope.exercises = {};
		$scope.exercise_entries = {};
		// weight
		$scope.weight = "";
		$scope.edit_weight = false;
		//other
		$scope.date = {};
		$scope.autocomplete = {
			exercise: {},
			menu: {},
			food: {}
		};
		$scope.loading = false;

		//=============errors=============

		$scope.errors = {};

		//=============food=============
		$scope.food_popup = {};
		$scope.menu_item = {}; //id, name, type. for displaying the chosen autocompleted option (food or recipe)
		$scope.food = {};//id, name. for displaying the chosen autocompleted option. Taken from $scope.menu_item.
		$scope.recipe = {
			temporary_contents: []
		}; //id, name, contents, temporary_contents, temporary_contents_clone (for calculating portions based on the original quantities).
		$scope.food_id = "";//probably should change this to an object. for the food popup-the food_id of the clicked on food that brings up the popup.
		$scope.food_name = "";//probably should change this to an object. likewise, for the food popup	
		
		$scope.foods = {}; //all foods
		$scope.recipes = {};//all recipes
		$scope.menu = {};//all foods plus all recipes
		$scope.food_entries = {};//all foods/recipes entered on a given day
		$scope.calories = {};//calorie info for a given day

		$scope.units = {
			food: {},
			exercise: {}
		};
		$scope.unit_id = ""; //for the select element in the recipe popup

		//associated food units
		$scope.all_foods_with_units = {};//all foods, with their associated units and default unit		
		$scope.assoc_units = {};//associated units for one chosen food. This is made from $scope.all_foods_with_units.
		$scope.food_and_assoc_units_array = {};//for the food popup, with checked state and calorie info. Associated units of one food. I could probably combine this info all into $scope.all_foods_with_units and get rid of this.

		// ===========================dates===========================

		if ($scope.date.typed === undefined) {
			$scope.date.typed = Date.parse('today').toString('dd/MM/yyyy');
		}
		$scope.date.long = Date.parse($scope.date.typed).toString('dd MMM yyyy');

		$scope.today = function () {
			$scope.date.typed = date.today();
		};
		$scope.changeDate = function ($keycode) {
			if ($keycode === 13) {
				$scope.date.typed = date.changeDate($keycode, $("#date").val());
			}
		};
		$scope.resetArray = function ($array) {
			$array.length = 0;
		};
		$scope.goToDate = function ($number) {
			$scope.date.typed = date.goToDate($scope.date.typed, $number);
		};

		// ========================================================================
		// ========================================================================
		// ===============================plugins==================================
		// ========================================================================
		// ========================================================================
		
		$(".wysiwyg").wysiwyg();

		// ========================================================================
		// ========================================================================
		// ===============================watches==================================
		// ========================================================================
		// ========================================================================

		$scope.$watch('date.typed', function (newValue, oldValue) {
			$scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
			$scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
			$("#date").val(newValue);

			if (newValue === oldValue) {
				$scope.pageLoad();
			}
			else {
				$scope.getEntries();
			}
		});

		// $scope.$watch('food.id', function (newValue) {
		// 	$scope.getAssocUnits();
		// });

		$scope.$watch('recipe.portion', function (newValue) {
			$($scope.recipe.temporary_contents).each(function () {
				if (this.original_quantity) {
					//making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
					this.quantity = this.original_quantity * newValue;
				}
			});
		});

		// ========================================================================
		// ========================================================================
		// =================================popups=================================
		// ========================================================================
		// ========================================================================

		$scope.showDefaultExerciseUnitPopup = function ($exercise) {
			$scope.selected.exercise = $exercise;
			$scope.show.default_exercise_unit_popup = true;
		};

		$scope.showRecipePopup = function ($recipe) {
			$scope.selected.recipe = $recipe;
			select.recipeContents($recipe.id).then(function (response) {
				$scope.show.popups.recipe = true;
				$scope.recipe.contents = response.data.contents;
				$scope.recipe.steps = response.data.steps;
			});
		};

		$scope.showTemporaryRecipePopup = function () {
			if (!$scope.recipe.temporary_contents || $scope.recipe.temporary_contents.length === 0)
			//Bring up the temporary recipe popup. No need to press enter again because quantity is irrelevant.
			select.displayRecipeContents($scope.recipe.id, $scope.recipe.name).then(function (response) {
				$scope.recipe.temporary_contents = response.data.contents;

				$($scope.recipe.temporary_contents).each(function () {
					this.original_quantity = this.quantity;
				});
				// $scope.recipe.temporary_contents_clone = response.data.contents;
			});
		};

		// ========================================================================
		// ========================================================================
		// =================================select=================================
		// ========================================================================
		// ========================================================================

		$scope.changeTab = function ($tab) {
			$scope.tab = {};
			$scope.tab[$tab] = true;
		};

		// ===========================page load===========================

		$scope.pageLoad = function () {
			select.pageLoad($scope.date.sql).then(function (response) {
				$scope.foods = response.data.foods;
				$scope.food_entries = response.data.food_entries;
				$scope.exercise_entries = response.data.exercise_entries;
				$scope.recipes = response.data.recipes;
				$scope.units.food = response.data.food_units;
				$scope.units.exercise = response.data.exercise_units;
				$scope.all_foods_with_units = response.data.foods_with_units;
				$scope.weight = response.data.weight;
				$scope.units.exercise = response.data.exercise_units;
				$scope.exercises = response.data.exercises;
				$scope.getMenu($scope.foods, $scope.recipes);
				$scope.journal_entry = response.data.journal_entry;
				$scope.exercise_tags = response.data.exercise_tags;

				$scope.selected.exercise_unit_id = $scope.units.exercise[0].id;
			});
		};

		//exercises, entries on date,

		// ===========================entries===========================

		$scope.getEntries = function () {
			select.entries($scope.date.sql).then(function (response) {
				$scope.weight = response.data.weight;
				$scope.exercise_entries = response.data.exercise_entries;
				$scope.journal_entry = response.data.journal_entry;	

				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
			});
		};

		// ===========================recipes===========================


		// ===========================menu===========================

		$scope.getMenu = function () {
			if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
				$scope.menu = select.getMenu($scope.foods, $scope.recipes);
			}
		};

		// ===========================assoc food units===========================

		// $scope.getAllFoodsWithUnits = function () {
		// 	$scope.loading = true;
		// 	select.getAllFoodsWithUnits().then(function (response) {
		// 		$scope.all_foods_with_units = response.data;
		// 		$scope.loading = false;
		// 	});
		// };

		$scope.getAssocUnits = function () {
			//for just one food
			for (var i = 0; i < $scope.all_foods_with_units.length; i++) {
				var $iteration = $scope.all_foods_with_units[i];
				var $iteration_food_id = $iteration.food.food_id;

				if ($iteration_food_id === $scope.food.id) {
					$scope.selected.food.assoc_units = $iteration.units;
				}
			}
		};

		$scope.displayAssocUnitOptions = function () {
			for (var i = 0; i < $scope.all_foods_with_units.length; i++) {
				var $iteration = $scope.all_foods_with_units[i];
				var $iteration_food_id = $iteration.food.id;

				if ($iteration_food_id == $scope.selected.food.id) {
					// $scope.food_with_assoc_units = $iteration;
					$scope.selected.food.assoc_units = $iteration.units;
					$scope.selected.unit.id = $iteration.food.default_unit_id;
					
				}
			}
			
		};

		$scope.getFoodInfo = function ($food_id, $food_name) {
			//for popup where user selects units for food and enters calories
			$scope.loading = true;
			$scope.food_popup.id = $food_id;
			$scope.food_popup.name = $food_name;
			$scope.show.food_info = true;
			select.foodInfo($food_id, $food_name).then(function (response) {
				$scope.food_popup.info = response.data;
			});
			
		};

		// ========================================================================
		// ========================================================================
		// =================================insert=================================
		// ========================================================================
		// ========================================================================

		$scope.insertExerciseTag = function ($keypress) {
			if ($keypress !== 13) {
				return;
			}
			insert.exerciseTag().then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.insertTagsInExercise = function () {
			//deletes tags from the exercise then adds the correct ones
			insert.tagsInExercise($scope.selected.exercise.id, $scope.selected.exercise.tags).then(function (response) {
				$scope.exercises = response.data;
				$scope.show.default_exercise_unit_popup = false;
			});
		};

		$scope.insertOrUpdateJournalEntry = function () {
			insert.journalEntry($scope.date.sql, $scope.journal_entry.text).then(function (response) {
				$scope.journal_entry = response.data;
			});
		};

		$scope.insertRecipe = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			insert.recipe($scope.new_item.recipe.name).then(function (response) {
				$scope.recipes = response.data;
			});
		};

		$scope.insertOrDeleteUnitInCalories = function ($unit_id, $checked_previously) {
			if (!$checked_previously) {
				//we are inserting the unit
				insert.unitInCalories($scope.food_popup.id, $unit_id, $checked_previously).then(function (response) {
					$scope.food_popup.info = response.data;
				});
			}
			else {
				//we are deleting the unit
				deleteItem.unitFromCalories($scope.food_popup.id, $unit_id).then(function (response) {
					$scope.food_popup.info = response.data;
				});
			}
			
		};

		// $scope.insert = function ($keycode, $table, $func) {
		// 	if ($keycode === 13) {
		// 		var $param = $table;
		// 		insert.insert($table).then(function (response) {
		// 			// $func($param);
		// 		});
		// 	}
		// };

		$scope.insertFood = function ($keycode) {
			if ($keycode === 13) {
				insert.food().then(function (response) {
					$scope.all_foods_with_units = response.data;
				});
			}
		};

		$scope.insertExercise = function ($keycode) {
			if ($keycode === 13) {
				insert.exercise().then(function (response) {
					$scope.exercises = response.data;
				});
			}
		};

		$scope.insertMenuEntry = function () {
			$scope.loading = true;
			$scope.new_entry.food.id = $scope.selected.food.id;
			$scope.new_entry.food.name = $scope.selected.food.name;
			$scope.new_entry.food.unit_id = $("#food-unit").val();

			insert.menuEntry($scope.date.sql, $scope.new_entry.food, $scope.recipe.temporary_contents).then(function (response) {
				$scope.food_entries = response.data;
				if ($scope.recipe.temporary_contents) {
					$scope.recipe.temporary_contents.length = 0;
				}
				$scope.loading = false;
			});
		};

		$scope.insertExerciseEntry = function () {
			$scope.new_entry.exercise.unit_id = $scope.selected.exercise_unit_id;
			insert.exerciseEntry($scope.date.sql, $scope.new_entry.exercise).then(function (response) {
				$scope.exercise_entries = response.data;
			});
		};

		$scope.insertExerciseUnit = function ($keycode) {
			if ($keycode === 13) {
				insert.exerciseUnit().then(function (response) {
					$scope.units.exercise = response.data;
				});
			}
		};

		$scope.insertOrUpdateWeight = function ($keycode) {
			if ($keycode === 13) {
				insert.weight($scope.date.sql).then(function (response) {
					$scope.weight = response.data;
					$scope.edit_weight = false;
					$("#weight").val("");
				});
			}
		};

		// ========================================================================
		// ========================================================================
		// =================================update=================================
		// ========================================================================
		// ========================================================================

		$scope.updateDefaultExerciseQuantity = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			update.defaultExerciseQuantity($scope.selected.exercise.id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.editWeight = function () {
			$scope.edit_weight = true;
			setTimeout(function () {
				$("#weight").focus();
			}, 500);
		};

		// $scope.updateJournalEntry = function () {
		// 	update.journalEntry($scope.date.sql, $scope.journal_entry.id, $scope.journal_entry.text).then(function (response) {
		// 		$scope.journal_entry = response.data;
		// 	});
		// };

		$scope.updateDefaultExerciseUnit = function ($unit_id) {
			update.defaultExerciseUnit($scope.selected.exercise.id, $unit_id).then(function (response) {
				$scope.exercises = response.data;
				$scope.show.default_exercise_unit_popup = false;
			});
		};

		$scope.updateCalories = function ($keycode, $unit_id, $calories) {
			if ($keycode === 13) {
				update.calories($scope.food_popup.id, $unit_id, $calories).then(function (response) {
					$scope.food_popup.info = response.data;
				});
			}
		};

		$scope.updateDefaultUnit = function ($unit_id) {
			update.defaultUnit($scope.food_popup.id, $unit_id).then(function (response) {
				$scope.food_popup.info = response.data;
			});
		};

		// ========================================================================
		// ========================================================================
		// ==============================autocomplete==============================
		// ========================================================================
		// ========================================================================

		$scope.autocompleteExercise = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				select.autocompleteExercise().then(function (response) {
					//fill the dropdown
					$scope.autocomplete.exercise = response.data;
					//show the dropdown
					$scope.show.autocomplete.new_exercise_entry = true;
					//select the first item
					$scope.autocomplete.exercise[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete.exercise);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete.exercise);
			}
		};

		$scope.autocompleteMenu = function ($keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				$scope.autocomplete.menu = select.autocompleteMenu($scope.menu);
				//show the dropdown
				$scope.show.autocomplete.new_menu_entry = true;
				//select the first item
				$scope.autocomplete.menu[0].selected = true;
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete.menu);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete.menu);
				console.log('something');
			}
		};

		$scope.autocompleteFood = function ($keycode, $typing) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				select.autocompleteFood($typing).then(function (response) {
					$scope.autocomplete.food = response.data;
					//show the dropdown
					$scope.show.autocomplete.food = true;
					//select the first item
					$scope.autocomplete.food[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.autocomplete.food);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.autocomplete.food);
				console.log('something');
			}
		};

		$scope.finishExerciseAutocomplete = function ($array, $selected) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			$selected = $selected || _.findWhere($array, {selected: true});
			$scope.selected.exercise = $selected;
			$scope.selected.exercise_unit_id = $scope.selected.exercise.default_exercise_unit_id;
			$scope.new_entry.exercise = $selected;
			$scope.new_entry.exercise.quantity = $scope.selected.exercise.default_quantity;
			$scope.selected.exercise = $selected;
			$scope.show.autocomplete.new_exercise_entry = false;
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
			$scope.show.autocomplete.new_menu_entry = false;
			$($set_focus).val("").focus();
		};

		$scope.finishFoodAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.recipe_popup.food = $selected;
			$scope.selected.food = $selected;
			$scope.show.autocomplete.food = false;
			$($set_focus).val("").focus();
		};

		$scope.insertOrAutocompleteExerciseEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete.new_exercise_entry) {
				//if enter is for the autocomplete
				$scope.finishExerciseAutocomplete($scope.autocomplete.exercise);
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
			if ($scope.show.autocomplete.new_menu_entry) {
				//if enter is for the autocomplete
				$scope.finishMenuAutocomplete($scope.autocomplete.menu, $("#food-quantity"));
				$scope.displayAssocUnitOptions();

				if ($scope.menu_item.type === 'recipe') {
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
			if ($scope.show.autocomplete.food) {
				//enter is for the autocomplete
				$scope.finishFoodAutocomplete($scope.autocomplete.food, $("#recipe-popup-food-quantity"));
				$scope.displayAssocUnitOptions();
			}
			else {
				// if enter is to add the entry
				$scope.insertFoodIntoRecipe();
			}
		};

		$scope.insertFoodIntoRecipe = function () {
			//we are adding a food to a permanent recipe
			var $data = {
				recipe_id: $scope.selected.recipe.id,
				food_id: $scope.selected.food.id,
				unit_id: $scope.selected.unit.id,
				quantity: $scope.recipe_popup.food.quantity
			};

			insert.foodIntoRecipe($data).then(function (response) {
				$scope.recipe.contents = response.data;
			});
			$("#recipe-popup-food-input").val("").focus();
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
			
			$("#temporary-recipe-popup-food-input").val("").focus();
		};

		// $scope.autocomplete = function ($object) {
		// 	var $keycode = $object.keycode;
		// 	var $property = $object.autocomplete_property;
		// 	var $show_property = $object.show_property;
		// 	var $function_property = $object.function_property;
		// 	var $params = $object.function_params;

		// 	if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
		// 		//not enter, up arrow or down arrow
		// 		select[$function_property]($params).then(function (response) {
		// 			//fill the dropdown
		// 			$scope.autocomplete[$property] = response.data;
		// 			//show the dropdown
		// 			$scope.show.autocomplete[$show_property] = true;
		// 			//select the first item
		// 			$scope.autocomplete[$property][0].selected = true;
		// 		});
		// 	}
		// 	else if ($keycode === 38) {
		// 		//up arrow pressed
		// 		autocomplete.autocompleteUpArrow($scope.autocomplete[$property]);
				
		// 	}
		// 	else if ($keycode === 40) {
		// 		//down arrow pressed
		// 		autocomplete.autocompleteDownArrow($scope.autocomplete[$property]);
		// 	}
		// };

		// ========================================================================
		// ========================================================================
		// =================================delete=================================
		// ========================================================================
		// ========================================================================

		$scope.deleteExerciseTag = function ($id) {
			deleteItem.exerciseTag($id).then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.deleteFoodFromRecipe = function ($id) {
			deleteItem.foodFromRecipe($id, $scope.selected.recipe.id).then(function (response) {
				$scope.recipe.contents = response.data;
			});
		};

		$scope.deleteRecipe = function ($id) {
			deleteItem.recipe($id).then(function (response) {
				$scope.recipes = response.data;
			});
		};

		$scope.deleteFood = function ($id) {
			deleteItem.food($id).then(function (response) {
				$scope.all_foods_with_units = response.data;
			});
		};

		$scope.deleteExercise = function ($id) {
			deleteItem.exercise($id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.deleteExerciseUnit = function ($id) {
			deleteItem.exerciseUnit($id).then(function (response) {
				$scope.units.exercise = response.data;
			});
		};

		$scope.deleteFoodUnit = function ($id) {
			deleteItem.foodUnit($id).then(function (response) {
				$scope.units.food = response.data;
			});
		};

		$scope.deleteFoodEntry = function ($id) {
			deleteItem.foodEntry($id, $scope.date.sql).then(function (response) {
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
			});
		};

		$scope.deleteExerciseEntry = function ($id) {
			deleteItem.exerciseEntry($id, $scope.date.sql).then(function (response) {
				$scope.exercise_entries = response.data;
			});
		};

		$scope.deleteFromTemporaryRecipe = function ($item) {
			$scope.recipe.temporary_contents = _.without($scope.recipe.temporary_contents, $item);
		};

		// $scope.deleteItem = function ($table, $item, $id, $func) {
		// 	deleteItem.deleteItem($table, $item, $id).then(function (response) {
		// 		if ($table === 'food_recipe') {
		// 			$scope.displayRecipeContents($scope.recipe.id, $scope.recipe.name);
		// 		}
		// 		else if ($table === 'food_entries') {
		// 			$scope.displayFoodEntries($scope.date.sql);
		// 		}
		// 		else {
		// 			$func();
		// 			if ($table === 'foods') {
		// 				$scope.getAllFoodsWithUnits();
		// 			}
		// 		}
		// 	});
		// };

		// ========================================================================
		// ========================================================================
		// ==============================media queries==============================
		// ========================================================================
		// ========================================================================

		enquire.register("screen and (min-width: 600px", {
			match: function () {
				// $("body").css('background', blue);
				if ($scope.tab.food_entries || $scope.tab.exercise_entries) {
					$scope.changeTab('entries');
					$scope.$apply();
				}
			},
			unmatch: function () {
				if ($scope.tab.entries) {
					$scope.changeTab('food_entries');
					$scope.$apply();
				}
			}
		});

		enquire.register("screen and (max-width: 890px", {
			match: function () {
				$("#avg-calories-for-the-week-text").text('Avg: ');
			},
			unmatch: function () {
				$("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
			}
		});

		// ========================================================================
		// ========================================================================
		// ==============================other==============================
		// ========================================================================
		// ========================================================================

		$scope.quickRecipe = function () {
			//remove any previous error styling so it doesn't wreck up the html
			$("#quick-recipe > *").removeAttr("style");

			var $string = $("#quick-recipe").html();

			var $lines = quickRecipe.formatString($string);

			var $contents = [];
			var $new_line;
			var $character;
			var $unit_name;
			var $description;
			var $food_name;
			var $item = {};
			var $errors = [];
			var $line_number = 0;
			var $steps = [];
			var $method = false;

			$($lines).each(function () {
				var $line = this;
				$line_number++;

				if ($line.toLowerCase() === "Method".toLowerCase()) {
					$method = true;
				}

				if (!$method) {
					for (var $index = 0; $index < $line.length; $index++) {
						$character = $line.substr($index, 1);

						//get the quantity
						if (!$item.quantity) {
							if (isNaN($character) && $character !== '.') {
								//not a number or a decimal point
							}
							//the following if check is so that quantity is not defined when it has not been specified
							else if ($character !== " ") {
								//the quantity is a valid number
								$quantity_response = quickRecipe.quantity($line, $index);
								$quantity = $quantity_response[0];
								$end_quantity_index = $quantity_response[1];
								$item.quantity = $quantity;
							}
						}
						
						//get the unit. unit is one word.
						if (!$item.unit_name && $item.quantity && $index >= $end_quantity_index) {	
							var $unit_response = quickRecipe.unitName($line, $end_quantity_index);
							if (!$unit_response.error) {
								//no error
								$unit_name = $unit_response.name;
								$end_unit_index = $unit_response.end_index;
								$item.unit_name = $unit_name;
							}
						}
						
						//get the food
						if (!$item.food_name && $item.unit_name && $index >= $end_unit_index) {
							var $food_response = quickRecipe.foodName($line, $end_unit_index);
							$food_name = $food_response[0];
							$end_food_index = $food_response[1];
							$item.food_name = $food_name;
						}
						
						//get the description
						if (!$item.description && $item.food_name && $item.quantity && $item.unit_name && $index >= $end_food_index) {
							$description = quickRecipe.description($line, $end_food_index);
							$item.description = $description;
						}			
						
						//get the method. method is under the heading 'method'	

						//check if it's the end of a line, to check if all values were entered
						if ($index === $line.length - 1) {
							//it's the end of a line
							if (!$item.food_name || !$item.quantity || !$item.unit_name) {
								$errors.push('Food, quantity and unit have not all been specified on line ' + $line_number);
								$("#quick-recipe > *:nth-child(" + $line_number + ")").css('background', 'red');
							}
							else {
								$contents.push($item);
								$item = {};
							}
						}			
					}
				}
				else if ($line.toLowerCase() !== "Method".toLowerCase()) {
					//it is the method, not the ingredients
					$steps.push($line);
				}
				
			});

			$scope.errors.quick_recipe = $errors;

			if ($errors.length > 0) {
				return;
			}

			$scope.quick_recipe.contents = $contents;
			$scope.quick_recipe.steps = $steps;

			insert.quickRecipe($contents, $steps, true).then(function (response) {
				if (response.data.similar_names) {
					$scope.quick_recipe.similar_names = response.data.similar_names;
					$scope.show.popups.similar_names = true;
				}
				else {
					$scope.recipes = response.data.recipes;
					$scope.all_foods_with_units = response.data.foods_with_units;
					$scope.units.food = response.data.food_units;
				}	
			});
		};

		$scope.quickRecipeFinish = function () {
			//this is for entering the recipe after the similar name check is done

			//first do the foods
			$($scope.quick_recipe.similar_names.foods).each(function () {
				var $specified_food = this.specified_food.name;
				var $existing_food = this.existing_food.name;
				var $checked = this.checked;
				var $index = this.index;

				if ($checked === $existing_food) {
					//we are using the existing food rather than creating a new food. therefore, change $scope.quick_recipe.contents to use the correct food name.
					$scope.quick_recipe.contents[$index].food_name = $existing_food;
				}
			});

			//do the same for the units
			$($scope.quick_recipe.similar_names.units).each(function () {
				var $specified_unit = this.specified_unit.name;
				var $existing_unit = this.existing_unit.name;
				var $checked = this.checked;
				var $index = this.index;

				if ($checked === $existing_unit) {
					//we are using the existing unit rather than creating a new unit. therefore, change $scope.quick_recipe.contents to use the correct unit name.
					$scope.quick_recipe.contents[$index].unit_name = $existing_unit;
				}
			});

			insert.quickRecipe($scope.quick_recipe.contents, $scope.quick_recipe.steps, false).then(function (response) {
				$scope.recipes = response.data.recipes;
				$scope.all_foods_with_units = response.data.foods_with_units;
				$scope.units.food = response.data.food_units;
			});
		};

		$scope.closePopup = function ($event, $popup) {
			var $target = $event.target;
			if ($target.className === 'popup-outer') {
				$scope.show.popups[$popup] = false;
			}
			console.log('something');
		};

		// $(".popup-outer").on('click', function (event) {
		// 	if (event.target === this) {

		// 	}
		// });
		
	}); //end display controller

})();