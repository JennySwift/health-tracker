var $page = window.location.pathname;
var app = angular.module('foodApp', []);

(function () {

	// ===========================display controller===========================

	app.controller('display', function ($scope, $http, date, select, autocomplete, insert, deleteItem, update) {

		// ===========================$scope properties===========================

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

		$scope.units = {};//all food units, unit_name, unit_id
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
		

		// =============================================================
		// ===========================watches===========================
		// =============================================================

		$scope.$watch('date.typed', function (newValue) {
			// if (newValue) {
				$scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
				$scope.displayFoodEntries();
				$scope.displayExerciseEntries();
				$scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
				$("#date").val(newValue);
				$scope.displayWeight();
			// }
		});

		$scope.$watch('food.id', function (newValue) {
			$scope.getAssocUnits();
		});

		// ===========================display===========================
		// =============================================================
		// =============================================================

		$scope.changeTab = function ($tab) {
			$scope.tab = $tab;
		};

		// ===========================units===========================

		$scope.displayUnitList = function () {
			$scope.loading = true;
			select.displayUnitList().then(function (response) {
				$scope.units = response.data;
				$scope.loading = false;
			});
		};
		$scope.displayUnitList();

		// ===========================exercises===========================

		$scope.displayExerciseEntries = function () {
			$scope.loading = true;
			select.displayExercises($scope.date.sql).then(function (response) {
				$scope.exercise_entries = response.data;
				$scope.loading = false;
			});
		};

		$scope.displayExercises = function () {
			$scope.loading = true;
			select.displayExerciseList().then(function (response) {
				$scope.exercises = response.data;
				$scope.loading = false;
			});
		};
		$scope.displayExercises();

		// ===========================weight===========================

		$scope.displayWeight = function () {
			$scope.loading = true;
			select.displayWeight($scope.date.sql).then(function (response) {
				// console.log('something');
				$scope.weight = response.data;
				if ($scope.weight === false) {
					$scope.weight = "N/A";
				}
				$scope.loading = false;
			});
		};

		// ===========================foods===========================

		$scope.displayFoodEntries = function () {
			$scope.loading = true;
			select.displayFoodEntries($scope.date.sql).then(function (response) {
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.loading = false;
			});
		};
		
		// $scope.displayFoods = function () {
		// 	$scope.loading = true;
		// 	select.displayFoodList().then(function (response) {
		// 		$scope.foods = response.data;
		// 		$scope.getMenu();
		// 		$scope.loading = false;
		// 	});
		// };
		// $scope.displayFoods();

		// ===========================recipes===========================

		$scope.displayRecipeList = function () {
			$scope.loading = true;
			select.displayRecipeList().then(function (response) {
				$scope.recipes = response.data;
				$scope.getMenu();
				$scope.loading = false;
			});
		};
		$scope.displayRecipeList();

		$scope.displayRecipeContents = function ($recipe_id, $recipe_name) {
			$scope.loading = true;
			select.displayRecipeContents($recipe_id, $recipe_name).then(function (response) {
				$scope.recipe.contents = response.data.contents;
				$scope.recipe.id = $recipe_id;
				$scope.recipe.name = $recipe_name;
				$scope.loading = false;
			});
		};

		// ===========================menu===========================

		$scope.getMenu = function () {
			if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
				$scope.menu = select.getMenu($scope.foods, $scope.recipes);
			}
		};

		// ===========================assoc food units===========================

		$scope.getAllFoodsWithUnits = function () {
			$scope.loading = true;
			select.getAllFoodsWithUnits().then(function (response) {
				$scope.all_foods_with_units = response.data;
				$scope.loading = false;
			});
		};

		$scope.getAllFoodsWithUnits();

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
				var $iteration_food_id = $iteration.food.food_id;

				if ($iteration_food_id === $scope.food.id) {
					// $scope.food_with_assoc_units = $iteration;
					$scope.assoc_units = $iteration.units;
					$scope.unit_id = $iteration.food.default_unit_id;
					
				}
			}
			
		};

		$scope.displayFoodAndAssocUnits = function ($food_id, $food_name) {
			$scope.loading = true;
			var $url = 'ajax/display.php';
			var $table = "food_and_assoc_units";
			$scope.food_id = $food_id;
			$scope.food_name = $food_name;
			
			var $data = {
				table: $table,
				food_id: $food_id
			};

			$http.post($url, $data).success(function (response) {
				$scope.food_and_assoc_units_array = response.array;
				$scope.loading = false;
			});
		};

		// ===========================insert===========================

		$scope.insertUnitInCalories = function ($table, $checked_previously, $unit_id) {
			var $url = 'ajax/insert.php';

			var $data = {
				table: $table,
				food_id: $scope.food_id,
				unit_id: $unit_id,
				checked_previously: $checked_previously
			};

			$http.post($url, $data).success(function (response) {
				$scope.displayFoodAndAssocUnits($scope.food_id, $scope.food_name);
			});
		};

		$scope.insert = function ($keycode, $table, $func) {
			if ($keycode === 13) {
				var $param = $table;
				insert.insert($table).then(function (response) {
					$func($param);
					if ($table === 'foods') {
						$scope.getAllFoodsWithUnits();
					}
				});
			}
		};

		$scope.addMenuEntry = function () {
			$scope.loading = true;
			insert.addMenuEntry($scope.date.sql, $scope.menu_item, $scope.food.quantity, $scope.recipe.temporary_contents).then(function (response) {
				$scope.displayFoodEntries($scope.date.sql);
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

		// ===========================update===========================

		$scope.$watch('recipe.portion', function (newValue) {
			$($scope.recipe.temporary_contents).each(function () {
				if (this.original_quantity) {
					//making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
					this.quantity = this.original_quantity * newValue;
				}
			});
		});

		$scope.editWeight = function () {
			$scope.edit_weight = true;
			setTimeout(function () {
				$("#weight").focus();
			}, 500);
		};

		$scope.updateCalories = function ($keycode, $unit_id, $calories) {
			if ($keycode === 13) {
				update.updateCalories($scope.food_id, $scope.food_name, $unit_id, $calories).then(function () {
					$scope.displayFoodAndAssocUnits($scope.food_id, $scope.food_name);
				});
			}
		};

		$scope.updateDefaultUnit = function ($unit_id, $calories) {
			update.updateDefaultUnit($unit_id, $scope.food_id, $scope.food_name).then(function (response) {
				$scope.displayFoodAndAssocUnits($scope.food_id, $scope.food_name);
			});
		};

		// ===========================autocomplete===========================

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
							$scope.addMenuEntry();
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

		// ===========================delete===========================

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