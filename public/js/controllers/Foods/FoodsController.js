var app = angular.module('tracker');

(function () {
	app.controller('FoodsController', function ($scope, $http, FoodsFactory, QuickRecipeFactory, AutocompleteFactory) {
		
		$scope.all_foods_with_units = foods_with_units;
		$scope.selected = {};

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
		};
		
		$scope.food_popup = {};

		$scope.foods = {}; //all foods
		$scope.menu = {};//all foods and all recipes
		$scope.calories = {};

		$scope.new_item = {};

		/**
		 * plugins
		 */
		
		$(".wysiwyg").wysiwyg();

		$scope.getMenu = function () {
			if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
				$scope.menu = select.getMenu($scope.foods, $scope.recipes);
			}
		};

		$scope.getFoodInfo = function ($food) {
			//for popup where user selects units for food and enters calories
			$scope.food_popup.id = $food.id;
			$scope.food_popup.name = $food.name;
			$scope.show.popups.food_info = true;
			FoodsFactory.getFoodInfo($food).then(function (response) {
				$scope.food_popup = response.data;
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
				FoodsFactory.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
			else {
				// It is now checked. Add the unit to the food.
				FoodsFactory.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
		};

		$scope.insertFood = function ($keycode) {
			if ($keycode === 13) {
				FoodsFactory.insertFood().then(function (response) {
					$scope.all_foods_with_units = response.data;
				});
			}
		};

		$scope.updateCalories = function ($keycode, $unit_id, $calories) {
			if ($keycode === 13) {
				FoodsFactory.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
					$scope.food_popup = response.data;
				});
			}
		};

		$scope.updateDefaultUnit = function ($food_id, $unit_id) {
			FoodsFactory.updateDefaultUnit($food_id, $unit_id).then(function (response) {
				$scope.food_popup = response.data;
			});
		};

		$scope.deleteFood = function ($food) {
			FoodsFactory.deleteFood($food).then(function (response) {
				$scope.all_foods_with_units = response.data;
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
				AutocompleteFactory.food($typing).then(function (response) {
					$scope.recipe_popup.autocomplete_options = response.data;
					//show the dropdown
					$scope.show.autocomplete_options.foods = true;
					//select the first item
					$scope.recipe_popup.autocomplete_options[0].selected = true;
				});
			}
			else if ($keycode === 38) {
				//up arrow pressed
				AutocompleteFactory.autocompleteUpArrow($scope.recipe_popup.autocomplete_options);
				
			}
			else if ($keycode === 40) {
				//down arrow pressed
				AutocompleteFactory.autocompleteDownArrow($scope.recipe_popup.autocomplete_options);
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

		/**
		 * other
		 */
		
		$scope.closePopup = function ($event, $popup) {
			var $target = $event.target;
			if ($target.className === 'popup-outer') {
				$scope.show.popups[$popup] = false;
			}
		};
		
	});

})();