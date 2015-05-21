var app = angular.module('tracker');

(function () {
	app.controller('foods', function ($scope, $http, foods) {

		/**
		 * scope properties
		 */
		
		$scope.all_foods_with_units = foods_with_units;
		$scope.recipes = {
			all: recipes,
			filtered: recipes
		};
		$scope.recipe_tags = recipe_tags;
		
		//show
		$scope.show = {
			autocomplete_options: {
				menu_items: false,
				foods: false,
				temporary_recipe_foods: false
			},
			popups: {
				recipe: false,
				similar_names: false,
				temporary_recipe: false,
				food_info: false,
			}
		};

		//filter
		$scope.filter = {
			recipes: {
				tag_ids: []
			}
		};

		//autocomplete
		$scope.autocomplete_options = {
			temporary_recipe_foods: {}
		};

		//edit
		$scope.edit = {
			recipe_method: false
		};
		
		$scope.food_popup = {};
		$scope.menu_item = {}; //id, name, type. for displaying the chosen autocompleted option (food or recipe)
		$scope.food = {};//id, name. for displaying the chosen autocompleted option. Taken from $scope.menu_item.
		$scope.recipe = {
			temporary_contents: []
		}; //id, name, contents, temporary_contents, temporary_contents_clone (for calculating portions based on the original quantities).
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

		$scope.temporary_recipe_popup = {};

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
		
		$scope.$watch('recipe.portion', function (newValue, oldValue) {
			$($scope.recipe.temporary_contents).each(function () {
				if (this.original_quantity) {
					//making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
					this.quantity = this.original_quantity * newValue;
				}
			});
		});

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
			select.filterRecipes($scope.filter.recipes.tag_ids).then(function (response) {
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


		/**
		 * insert
		 */
		
		$scope.insertRecipeTag = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			insert.recipeTag().then(function (response) {
				$scope.recipe_tags = response.data;
			});
		};

		$scope.insertTagsIntoRecipe = function () {
			//deletes tags from the recipe then adds the correct ones
			insert.tagsIntoRecipe($scope.selected.recipe.id, $scope.selected.recipe.tags).then(function (response) {
				$scope.recipes.filtered = response.data;
			});
		};

		

		$scope.insertRecipe = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			insert.recipe($scope.new_item.recipe.name).then(function (response) {
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
		
		$scope.editRecipeMethod = function () {
			$scope.edit.recipe_method = true;
			var $text;
			var $string = "";

			//convert the array into a string so I can make the wysiwyg display the steps
			$($scope.recipe.steps).each(function () {
				$text = this.text;
				$text = $text + '<br>';
				// $text = '<div>' + $text + '</div>';
				$string+= $text;
			});
			$("#edit-recipe-method").html($string);
		};

		$scope.updateRecipeMethod = function () {
			//this is some duplication of insertRecipeMethod
			var $string = $("#edit-recipe-method").html();
			var $lines = quickRecipe.formatString($string, $("#edit-recipe-method"));
			var $steps = [];

			$($lines).each(function () {
				var $line = this;
				$steps.push($line);
			});

			update.recipeMethod($scope.selected.recipe.id, $steps).then(function (response) {
				$scope.recipe.contents = response.data.contents;
				$scope.recipe.steps = response.data.steps;
				$scope.edit.recipe_method = false;
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
			deleteItem.recipeTag($id).then(function (response) {
				$scope.recipe_tags = response.data;
			});
		};

		$scope.deleteFoodFromRecipe = function ($id) {
			deleteItem.foodFromRecipe($id, $scope.selected.recipe.id).then(function (response) {
				$scope.recipe.contents = response.data;
			});
		};

		$scope.deleteRecipe = function ($id) {
			deleteItem.recipe($id).then(function (response) {
				$scope.recipes.filtered = response.data;
			});
		};

		$scope.deleteFood = function ($id) {
			deleteItem.food($id).then(function (response) {
				$scope.all_foods_with_units = response.data;
			});
		};

		$scope.deleteFoodUnit = function ($id) {
			deleteItem.foodUnit($id).then(function (response) {
				$scope.units.food = response.data;
			});
		};

		$scope.deleteFromTemporaryRecipe = function ($item) {
			$scope.recipe.temporary_contents = _.without($scope.recipe.temporary_contents, $item);
		};
		
		/**
		 * popups
		 */
		
		$scope.showRecipePopup = function ($recipe) {
			$scope.selected.recipe = $recipe;
			select.recipeContents($recipe.id).then(function (response) {
				$scope.show.popups.recipe = true;
				$scope.recipe.contents = response.data.contents;
				$scope.recipe.steps = response.data.steps;
			});
		};

		$scope.showTemporaryRecipePopup = function () {
			$scope.show.popups.temporary_recipe = true;
			select.recipeContents($scope.selected.menu.id).then(function (response) {
				$scope.recipe.temporary_contents = response.data.contents;

				$($scope.recipe.temporary_contents).each(function () {
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

		/**
		 * quick recipe
		 */
		
		$scope.quickRecipe = function () {
			//remove any previous error styling so it doesn't wreck up the html
			$("#quick-recipe > *").removeAttr("style");

			var $string = $("#quick-recipe").html();

			var $lines = quickRecipe.formatString($string, $("#quick-recipe"));

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
			var $method_trigger_line_number;
			var $method = false;

			$($lines).each(function () {
				var $line = this;
				$line_number++;

				//acceptable method triggers are: method, directions, or preparation, any case, with or without a colon.
				if ($line.toLowerCase() === "Method".toLowerCase() || $line.toLowerCase() === "Method:".toLowerCase() || $line.toLowerCase() === "Preparation".toLowerCase() || $line.toLowerCase() === "Preparation:".toLowerCase() || $line.toLowerCase() === "Directions".toLowerCase() || $line.toLowerCase() === "Directions:".toLowerCase()) {
					$method = true;
					$method_trigger_line_number = $line_number;
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
				else if ($line_number !== $method_trigger_line_number) {
					//it is the actual method, not the ingredients or the method trigger line
					$steps.push($line);
				}
				
			});

			$scope.errors.quick_recipe = $errors;

			if ($errors.length > 0) {
				return;
			}

			$scope.quick_recipe.contents = $contents;
			$scope.quick_recipe.steps = $steps;
			$scope.quick_recipe.name = prompt('name your recipe');

			insert.quickRecipe($scope.quick_recipe.name, $contents, $steps, true).then(function (response) {
				if (response.data.similar_names) {
					$scope.quick_recipe.similar_names = response.data.similar_names;
					$scope.show.popups.similar_names = true;
				}
				else {
					$scope.recipes.filtered = response.data.recipes;
					$scope.all_foods_with_units = response.data.foods_with_units;
					$scope.units.food = response.data.food_units;
				}	
			});
		};

		$scope.insertRecipeMethod = function () {
			var $string = $("#recipe-method").html();
			var $lines = quickRecipe.formatString($string, $("#recipe-method"));
			var $steps = [];

			$($lines).each(function () {
				var $line = this;
				$steps.push($line);
			});

			insert.recipeMethod($scope.selected.recipe.id, $steps).then(function (response) {
				$scope.recipe.contents = response.data.contents;
				$scope.recipe.steps = response.data.steps;
			});
		};

		$scope.quickRecipeFinish = function () {
			//this is for entering the recipe after the similar name check is done
			$scope.show.popups.similar_names = false;

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

			insert.quickRecipe($scope.quick_recipe.name, $scope.quick_recipe.contents, $scope.quick_recipe.steps, false).then(function (response) {
				$scope.recipes.filtered = response.data.recipes;
				$scope.all_foods_with_units = response.data.foods_with_units;
				$scope.units.food = response.data.food_units;
			});
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
		
		
	}); //end controller

})();