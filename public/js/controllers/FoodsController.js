var app = angular.module('tracker');

(function () {
	app.controller('foods', function ($scope, $http, foods, quickRecipe, tags, autocomplete) {

		/**
		 * scope properties
		 */
		
		$scope.all_foods_with_units = foods_with_units;
		$scope.recipes = {
			all: recipes,
			filtered: recipes
		};
		$scope.recipe_tags = recipe_tags;

		$scope.selected = {};
		$scope.errors = {};
		
		//show
		$scope.show = {
			autocomplete_options: {
				menu_items: false,
				foods: false,
			},
			popups: {
				recipe: false,
				similar_names: false,
				food_info: false,
			},
			help: {
				quick_recipe: false
			}
		};

		//filter
		$scope.filter = {
			recipes: {
				tag_ids: []
			}
		};
		
		$scope.food_popup = {};
		$scope.menu_item = {}; //id, name, type. for displaying the chosen autocompleted option (food or recipe)
		$scope.food = {};//id, name. for displaying the chosen autocompleted option. Taken from $scope.menu_item.
		$scope.food_id = "";//probably should change this to an object. for the food popup-the food_id of the clicked on food that brings up the popup.
		$scope.food_name = "";//probably should change this to an object. likewise, for the food popup	
		
		$scope.foods = {}; //all foods
		$scope.menu = {};//all foods plus all recipes
		$scope.calories = {};//calorie info for a given day
		
		//quick recipe
		$scope.quick_recipe = {};

		//recipe_popup
		$scope.recipe_popup = {

		};

		//new item-eg new food, as opposed to to food entry
		$scope.new_item = {
			recipe: {}
		};

		//associated food units
		$scope.assoc_units = {};//associated units for one chosen food. This is made from $scope.all_foods_with_units.
		$scope.food_and_assoc_units_array = {};//for the food popup, with checked state and calorie info. Associated units of one food. I could probably combine this info all into $scope.all_foods_with_units and get rid of this.

		/**
		 * watches
		 */

		$scope.$watchCollection('filter.recipes.tag_ids', function (newValue, oldValue) {
			if (newValue !== oldValue) {
				$scope.filterRecipes();
			}
		});

		/**
		 * plugins
		 */
		
		$(".wysiwyg").wysiwyg();

		/**
		 * select
		 */
		
		$scope.filterRecipes = function () {
			foods.filterRecipes($scope.filter.recipes.tag_ids).then(function (response) {
				$scope.recipes.filtered = response.data;
			});
		};

		$scope.getMenu = function () {
			if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
				$scope.menu = select.getMenu($scope.foods, $scope.recipes);
			}
		};

		$scope.getFoodInfo = function ($food_id, $food_name) {
			//for popup where user selects units for food and enters calories
			$scope.food_popup.id = $food_id;
			$scope.food_popup.name = $food_name;
			$scope.show.popups.food_info = true;
			foods.getFoodInfo($food_id).then(function (response) {
				$scope.food_popup = response.data;
			});
			
		};

		// $scope.getAssocUnits = function () {
		// 	//for just one food
		// 	for (var i = 0; i < $scope.all_foods_with_units.length; i++) {
		// 		var $iteration = $scope.all_foods_with_units[i];
		// 		var $iteration_food_id = $iteration.food.food_id;

		// 		if ($iteration_food_id === $scope.food.id) {
		// 			$scope.selected.food.assoc_units = $iteration.units;
		// 		}
		// 	}
		// };

		/**
		 * insert
		 */
		
		$scope.insertRecipeTag = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			tags.insertRecipeTag().then(function (response) {
				$scope.recipe_tags = response.data;
			});
		};

		$scope.insertTagsIntoRecipe = function () {
			//deletes tags from the recipe then adds the correct ones
			$scope.recipe_popup.notification = 'Saving tags...';
			foods.insertTagsIntoRecipe($scope.recipe_popup.recipe.id, $scope.recipe_popup.tags).then(function (response) {
				$scope.recipe_popup.notification = 'Tags have been saved.';
				$scope.recipes.filtered = response.data;
			});
		};

		

		$scope.insertRecipe = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			foods.insertRecipe($scope.new_item.recipe.name).then(function (response) {
				$scope.recipes.filtered = response.data;
			});
		};

		/**
		 * Add a unit to a food or remove the unit from the food.
		 * The method name is old and should probably be changed.
		 * @param  {[type]} $unit_id            [description]
		 * @param  {[type]} $checked_previously [description]
		 * @return {[type]}                     [description]
		 */
		$scope.insertOrDeleteUnitInCalories = function ($unit_id) {
			//Check if the checkbox is checked
			if ($scope.food_popup.food_units.indexOf($unit_id) === -1) {
				//It is now unchecked. Remove the unit from the food.
				foods.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
			else {
				// It is now checked. Add the unit to the food.
				foods.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
		};

		$scope.insertFood = function ($keycode) {
			if ($keycode === 13) {
				foods.insertFood().then(function (response) {
					$scope.all_foods_with_units = response.data;
				});
			}
		};

		/**
		 * update
		 */

		$scope.updateRecipeMethod = function () {
			//this is some duplication of insertRecipeMethod
			var $string = $("#edit-recipe-method").html();
			var $lines = quickRecipe.formatString($string, $("#edit-recipe-method")).items;
			var $steps = [];

			$($lines).each(function () {
				var $line = this;
				$steps.push($line);
			});

			foods.updateRecipeMethod($scope.recipe_popup.recipe.id, $steps).then(function (response) {
				$scope.recipe_popup = response.data;
				$scope.recipe_popup.edit_method = false;
			});	
		};

		$scope.updateCalories = function ($keycode, $unit_id, $calories) {
			if ($keycode === 13) {
				foods.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
		};

		$scope.updateDefaultUnit = function ($food_id, $unit_id) {
			foods.updateDefaultUnit($food_id, $unit_id).then(function (response) {
				$scope.food_popup = response.data;
			});
		};

		/**
		 * delete
		 */
		
		$scope.deleteRecipeTag = function ($id) {
			tags.deleteRecipeTag($id).then(function (response) {
				$scope.recipe_tags = response.data;
			});
		};

		$scope.deleteFoodFromRecipe = function ($food_id) {
			foods.deleteFoodFromRecipe($food_id, $scope.recipe_popup.recipe.id).then(function (response) {
				$scope.recipe_popup = response.data;
			});
		};

		$scope.deleteRecipe = function ($id) {
			foods.deleteRecipe($id).then(function (response) {
				$scope.recipes.filtered = response.data;
			});
		};

		$scope.deleteFood = function ($id) {
			foods.deleteFood($id).then(function (response) {
				$scope.all_foods_with_units = response.data;
			});
		};
		
		/**
		 * popups
		 */
		
		$scope.showRecipePopup = function ($recipe) {
			// $scope.selected.recipe = $recipe;
			foods.getRecipeContents($recipe.id).then(function (response) {
				$scope.show.popups.recipe = true;
				$scope.recipe_popup = response.data;
			});
		};

		/**
		 * quick recipe
		 */
		
		/**
		 * End goal of the function:
		 * Call foods.insertQuickRecipe, with $check_similar_names as true.
		 * Send the contents, steps, and name of new recipe.
		 * The PHP checks for similar names and returns similar names if found.
		 * The JS checks for similar names in the response.
		 * If they exist, a popup shows. From there, the user can click a button which fires $scope.quickRecipeFinish,
		 * sending the recipe info again but this time without the similar name check.
		 * If none exist, the recipe should have been entered with the PHP and things should update accordingly on the page.
		 * @return {[type]} [description]
		 */
		$scope.quickRecipe = function () {
			//remove any previous error styling so it doesn't wreck up the html
			$("#quick-recipe > *").removeAttr("style");
			//Empty the errors array from any previous attempts
			$scope.errors.quick_recipe = [];
			//Hide the errors div because even with emptying the scope property, the display is slow to update.
			$("#quick-recipe-errors").hide();

			var $string = $("#quick-recipe").html();
			//Recipe is an object, with an array of items and an array of steps.
			var $recipe = quickRecipe.formatString($string, $("#quick-recipe"));
			var $line;
			var $items = [];
			var $method = $recipe.method;

			//Populate items array
			$items = quickRecipe.populateItemsArray($recipe.items);

			//check item contains quantity, unit and food
			//and convert quantities to decimals if necessary
			$items_and_errors = quickRecipe.errorCheck($items);
			$items = $items_and_errors.items;
			$errors = $items_and_errors.errors;

			if ($errors.length > 0) {
				$scope.errors.quick_recipe = $errors;
				$("#quick-recipe-errors").show();
				return;
			}

			//Prompt the user for the recipe name
			var $recipe_name = prompt('name your recipe');

			//If the user changes their mind and cancels
			if (!$recipe_name) {
				return;
			}

			$recipe = {
				name: $recipe_name,
				items: $items,
				steps: $method,
			};

			$scope.quick_recipe = $recipe;

			//Attempt to insert the recipe. It won't be inserted if similar names are found.
			$scope.quickRecipeAttemptInsert($recipe);
		};

		$scope.quickRecipeAttemptInsert = function ($recipe) {
			foods.insertQuickRecipe($recipe, true).then(function (response) {
				if (response.data.similar_names) {
					$scope.quick_recipe.similar_names = response.data.similar_names;
					$scope.show.popups.similar_names = true;
				}
				else {
					$scope.recipes.filtered = response.data.recipes;
					$scope.all_foods_with_units = response.data.foods_with_units;
				}	
			});
		};

		/**
		 * This is for entering the recipe after the similar name check is done.
		 * We call foods.insertQuickRecipe again, but this time with $check_similar_names parameter as false,
		 * so that the recipe gets entered.
		 * @return {[type]} [description]
		 */
		$scope.quickRecipeFinish = function () {
			$scope.show.popups.similar_names = false;

			//first do the foods
			$($scope.quick_recipe.similar_names.foods).each(function () {
				var $specified_food = this.specified_food.name;
				var $existing_food = this.existing_food.name;
				var $checked = this.checked;
				var $index = this.index;

				if ($checked === $existing_food) {
					//we are using the existing food rather than creating a new food. therefore, change $scope.quick_recipe.contents to use the correct food name.
					$scope.quick_recipe.items[$index].food = $existing_food;
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
					$scope.quick_recipe.items[$index].unit = $existing_unit;
				}
			});

			foods.insertQuickRecipe($scope.quick_recipe, false).then(function (response) {
				$scope.recipes.filtered = response.data.recipes;
				$scope.all_foods_with_units = response.data.foods_with_units;
			});
		};

		/**
		 * autocomplete food (for adding food to a recipe in the recipe popup)
		 */

		$scope.autocompleteFood = function ($keycode) {
			var $typing = $("#recipe-popup-food-input").val();
			if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
				//not enter, up arrow or down arrow
				//fill the dropdown
				autocomplete.food($typing).then(function (response) {
					$scope.recipe_popup.autocomplete_options = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.foods = true;
					//select the first item
					$scope.recipe_popup.autocomplete_options[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				autocomplete.autocompleteUpArrow($scope.recipe_popup.autocomplete_options);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				autocomplete.autocompleteDownArrow($scope.recipe_popup.autocomplete_options);
			}
		};

		$scope.finishFoodAutocomplete = function ($array, $set_focus) {
			//array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
			var $selected = _.findWhere($array, {selected: true});
			$scope.recipe_popup.food = $selected;
			$scope.selected.food = $selected;
			$scope.show.autocomplete_options.foods = false;
			$($set_focus).val("").focus();
		};

		$scope.insertOrAutocompleteFoodEntry = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			//enter is pressed
			if ($scope.show.autocomplete_options.foods) {
				//enter is for the autocomplete
				$scope.finishFoodAutocomplete($scope.recipe_popup.autocomplete_options, $("#recipe-popup-food-quantity"));
			}
			else {
				// if enter is to add the entry
				$scope.insertFoodIntoRecipe();
			}
		};

		$scope.insertFoodIntoRecipe = function () {
			//we are adding a food to a permanent recipe
			var $data = {
				recipe_id: $scope.recipe_popup.recipe.id,
				food_id: $scope.selected.food.id,
				unit_id: $("#recipe-popup-unit").val(),
				quantity: $scope.recipe_popup.food.quantity,
				description: $scope.recipe_popup.food.description
			};

			foods.insertFoodIntoRecipe($data).then(function (response) {
				$scope.recipe_popup = response.data;
			});
			$("#recipe-popup-food-input").val("").focus();
			$scope.recipe_popup.food.description = "";
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

		$scope.toggleQuickRecipeHelp = function () {
			$scope.show.help.quick_recipe = !$scope.show.help.quick_recipe;
		};

		$scope.toggleEditMethod = function () {
			//Toggle the visibility of the wysywig
			$scope.recipe_popup.edit_method = !$scope.recipe_popup.edit_method;

			//If we are editing the recipe, prepare the html of the wysiwyg
			if ($scope.recipe_popup.edit_method) {
				var $text;
				var $string = "";

				//convert the array into a string so I can make the wysiwyg display the steps
				$($scope.recipe_popup.steps).each(function () {
					$text = this.text;
					$text = $text + '<br>';
					// $text = '<div>' + $text + '</div>';
					$string+= $text;
				});
				$("#edit-recipe-method").html($string);
			}
		};		
		
	}); //end controller

})();