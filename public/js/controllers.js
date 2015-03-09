var $page = window.location.pathname;
var app = angular.module('foodApp', []);

(function () {
	app.controller('display', function ($scope, $http, date, select, autocomplete, insert, deleteItem, update) {

		// ========================================================================
		// ========================================================================
		// ============================$scope properties===========================
		// ========================================================================
		// ========================================================================

		//show
		$scope.show = {
			food_info: false
		};

		// exercise
		$scope.exercises = {};
		$scope.exercise_entries = {};
		// weight
		$scope.weight = "";
		$scope.edit_weight = false;
		//other
		$scope.date = {};
		$scope.autocomplete = {};
		$scope.tab = 'entries';
		$scope.loading = false;

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

		$scope.$watch('food.id', function (newValue) {
			$scope.getAssocUnits();
		});

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
		// =================================select=================================
		// ========================================================================
		// ========================================================================

		$scope.changeTab = function ($tab) {
			$scope.tab = $tab;
		};

		// ===========================page load===========================

		$scope.pageLoad = function () {
			select.pageLoad($scope.date.sql).then(function (response) {
				$scope.foods = response.data.foods;
				$scope.food_entries = response.data.food_entries;
				$scope.exercise_entries = response.data.exercise_entries;
				$scope.recipes = response.data.recipes;
				$scope.units.food = response.data.food_units;
				$scope.all_foods_with_units = response.data.foods_with_units;
				$scope.weight = response.data.weight;
				$scope.units.exercise = response.data.exercise_units;
				$scope.exercises = response.data.exercises;
				$scope.getMenu($scope.foods, $scope.recipes);
			});
		};

		//exercises, entries on date,

		// ===========================entries===========================

		$scope.getEntries = function () {
			select.entries($scope.date.sql).then(function (response) {
				$scope.weight = response.data.weight;
				$scope.exercise_entries = response.data.exercise_entries;

				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;		
			});
		};

		// ===========================recipes===========================

		// $scope.displayRecipeContents = function ($recipe_id, $recipe_name) {
		// 	$scope.loading = true;
		// 	select.displayRecipeContents($recipe_id, $recipe_name).then(function (response) {
		// 		$scope.recipe.contents = response.data.contents;
		// 		$scope.recipe.id = $recipe_id;
		// 		$scope.recipe.name = $recipe_name;
		// 		$scope.loading = false;
		// 	});
		// };

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
					$scope.food.assoc_units = $iteration.units;
				}
			}
		};

		$scope.displayAssocUnitOptions = function () {
			for (var i = 0; i < $scope.all_foods_with_units.length; i++) {
				var $iteration = $scope.all_foods_with_units[i];
				var $iteration_food_id = $iteration.food.id;

				if ($iteration_food_id == $scope.food.id) {
					// $scope.food_with_assoc_units = $iteration;
					$scope.assoc_units = $iteration.units;
					$scope.unit_id = $iteration.food.default_unit_id;
					
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

		$scope.insert = function ($keycode, $table, $func) {
			if ($keycode === 13) {
				var $param = $table;
				insert.insert($table).then(function (response) {
					// $func($param);
				});
			}
		};

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
			insert.menuEntry($scope.date.sql, $scope.menu_item, $scope.food.quantity, $scope.recipe.temporary_contents).then(function (response) {
				$scope.food_entries = response.data;
				if ($scope.recipe.temporary_contents) {
					$scope.recipe.temporary_contents.length = 0;
				}
				$scope.loading = false;
			});
		};

		$scope.addItemToRecipe = function () {
			insert.addItemToRecipe($scope.recipe, $scope.food, $scope.unit_id).then(function (response) {
				$scope.displayRecipeContents($scope.recipe.id, $scope.recipe.name);
			});
		};

		$scope.enterWeight = function ($keycode) {
			if ($keycode === 13) {
				insert.enterWeight($scope.date.sql).then(function (response) {
					$scope.displayWeight();
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

		$scope.editWeight = function () {
			$scope.edit_weight = true;
			setTimeout(function () {
				$("#weight").focus();
			}, 500);
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

		$scope.autocomplete = function ($type, $keycode) {
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//if keypress is not enter, up arrow, or down arrow
				if ($type === "menu") {
					$scope.autocomplete.menu = autocomplete.autocompleteMenu($scope.menu, $scope.menu_item.name);
				}
				else if ($type === "food") {
					$scope.autocomplete.food = autocomplete.autocompleteFood($scope.foods, $scope.food.name);
				}
				else if ($type === "exercise") {
					$scope.exercise_autocomplete = autocomplete.autocompleteExercise();
				}
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow();
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow();
			}
		};

		$scope.enter = function ($keycode, $type, $purpose) {
			if ($keycode === 13) {
				//if enter is pressed
				if ($(".selected").length > 0) {
					//if enter is for the autocomplete
					$scope.finishAutocomplete($type);

					if ($scope.menu_item.type === 'recipe') {
						if (!$scope.recipe.temporary_contents || $scope.recipe.temporary_contents.length === 0)
						//Bring up the temporary recipe popup. No need to press enter again because quantity is irrelevant.
						select.displayRecipeContents($scope.recipe.id, $scope.recipe.name).then(function (response) {
							$scope.recipe.temporary_contents = response.data.contents;

							$($scope.recipe.temporary_contents).each(function () {
								this.original_quantity = this.quantity;
							});
							// $scope.recipe.temporary_contents_clone = response.data.contents;
						});
					}

					
				}
				else {
					// if enter is to add the entry
					if ($type === 'menu') {
						if ($scope.menu_item.type === 'food') {
							$scope.insertMenuEntry();
						}
						// else if ($scope.menu_item.type === 'recipe') {
						// 	//we want to edit the recipe before entering it
						// 	select.displayRecipeContents($scope.recipe.id, $scope.recipe.name).then(function (response) {
						// 		$scope.recipe.temporary_contents = response.data.contents;
						// 	});
						// }
					}
					else if ($type === 'food') {
						if ($purpose === 'temporary_recipe') {
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
						}
						else {
							//we are adding a food to a permanent recipe
							$scope.addItemToRecipe();
							$("#recipe-popup-food-input").val("").focus();
						}
					}
					else if ($type === 'exercise') {
						addEntry("exercise_entries", display, "exercise_entries");
					}
				}
			}
		};

		$scope.finishAutocomplete = function ($type) {
			if ($type === 'menu') {
				$scope.menu_item.id = $(".selected").attr('data-id');
				$scope.menu_item.name = $(".selected").text();
				$scope.menu_item.type = $(".selected").attr('data-type');

				if ($scope.menu_item.type === 'food') {
					$scope.food.id = $(".selected").attr('data-id');
					$scope.food.name = $(".selected").text();
					$("#food-quantity").val("").focus();
					//populating the units select element
					$scope.displayAssocUnitOptions();
				}
				else if ($scope.menu_item.type === 'recipe') {
					$scope.recipe.id = $(".selected").attr('data-id');
					$scope.recipe.name = $(".selected").text();
					// $("#food-quantity").val("").focus();
				}

				$scope.autocomplete.menu = "";
			}
			else if ($type === 'food') {
				$scope.food.id = $(".selected").attr('data-id');
				$scope.food.name = $(".selected").text();
				$scope.autocomplete.food = "";

				if ($scope.recipe.temporary_contents.length > 0) {
					//the autocomplete is for the temporary recipe editing
					$("#temporary-recipe-popup-food-quantity").val("").focus();
				}
				else {
					//the autocomplete is for the permanent recipe editing
					$("#recipe-popup-food-quantity").val("").focus();
				}
				
				//populating the units select element (if recipe is not selected)
				if ($scope.menu_item.type === "food" || $type === 'food') {
					$scope.displayAssocUnitOptions();
				}
			}
			else if ($type === 'exercise') {

			}
		};

		// ========================================================================
		// ========================================================================
		// =================================delete=================================
		// ========================================================================
		// ========================================================================

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

		$scope.deleteFoodEntry = function ($id) {
			deleteItem.foodEntry($id, $scope.date.sql).then(function (response) {
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
			});
		};

		$scope.deleteFromTemporaryRecipe = function ($item) {
			$scope.recipe.temporary_contents = _.without($scope.recipe.temporary_contents, $item);
		};

		$scope.deleteItem = function ($table, $item, $id, $func) {
			deleteItem.deleteItem($table, $item, $id).then(function (response) {
				if ($table === 'recipe_entries') {
					$scope.displayRecipeContents($scope.recipe.id, $scope.recipe.name);
				}
				else if ($table === 'food_entries') {
					$scope.displayFoodEntries($scope.date.sql);
				}
				else {
					$func();
					if ($table === 'foods') {
						$scope.getAllFoodsWithUnits();
					}
				}
			});
		};

		
	}); //end display controller

})();