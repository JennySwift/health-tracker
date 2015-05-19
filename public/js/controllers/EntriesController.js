var app = angular.module('tracker');

(function () {
	app.controller('entries', function ($scope, $http, date, entries, weights) {

		/**
		 * scope properties
		 */
		
		$scope.entries = {
			menu: [],
			exercise: []
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
		
		$scope.weight = "";
		$scope.edit_weight = false;

		$scope.date = {};
		
		if ($scope.date.typed === undefined) {
			$scope.date.typed = Date.parse('today').toString('dd/MM/yyyy');
		}
		$scope.date.long = Date.parse($scope.date.typed).toString('dd MMM yyyy');

		$scope.goToDate = function ($number) {
			$scope.date.typed = date.goToDate($scope.date.typed, $number);
		};

		$scope.today = function () {
			$scope.date.typed = date.today();
		};
		$scope.changeDate = function ($keycode) {
			if ($keycode === 13) {
				$scope.date.typed = date.changeDate($keycode, $("#date").val());
			}
		};

		//Just defining these here after I refactred the controllers so it doesn't error.
		$scope.calories = {};
		$scope.units = {};
		$scope.recipes = {};

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
		
		/**
		 * select
		 */
		
		// $scope.pageLoad = function () {
		// 	select.pageLoad($scope.date.sql).then(function (response) {
		// 		$scope.foods = response.data.foods;
		// 		$scope.food_entries = response.data.food_entries;
		// 		$scope.calories.day = response.data.calories_for_the_day;
		// 		$scope.calories.week_avg = response.data.calories_for_the_week;
		// 		$scope.exercise_entries = response.data.exercise_entries;
		// 		$scope.recipes.filtered = response.data.recipes;
		// 		$scope.units.food = response.data.food_units;
		// 		$scope.units.exercise = response.data.exercise_units;
		// 		$scope.all_foods_with_units = response.data.foods_with_units;
		// 		$scope.weight = response.data.weight;
		// 		$scope.units.exercise = response.data.exercise_units;
		// 		$scope.exercises = response.data.exercises;
		// 		$scope.exercise_series = response.data.exercise_series;
		// 		// $scope.getMenu($scope.foods, $scope.recipes);
		// 		$scope.journal_entry = response.data.journal_entry;
		// 		$scope.exercise_tags = response.data.exercise_tags;
		// 		$scope.workouts = response.data.workouts;
		// 		$scope.recipe_tags = response.data.recipe_tags;

		// 		$scope.selected.exercise_unit.id = $scope.units.exercise[0].id;
		// 	});
		// };

		/**
		 * Get all the user's entries for the current date
		 */
		$scope.getEntries = function () {
			entries.getEntries($scope.date.sql).then(function (response) {
				$scope.weight = response.data.weight;
				$scope.entries.exercise = response.data.exercise_entries;
				$scope.journal_entry = response.data.journal_entry;	

				$scope.entries.menu = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
			});
		};

		/**
		 * insert
		 */
		
		$scope.insertMenuEntry = function () {
			$scope.new_entry.food.id = $scope.selected.food.id;
			$scope.new_entry.food.name = $scope.selected.food.name;
			$scope.new_entry.food.unit_id = $("#food-unit").val();

			insert.menuEntry($scope.date.sql, $scope.new_entry.food).then(function (response) {
				$scope.entries.menu = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;

				if ($scope.recipe.temporary_contents) {
					$scope.recipe.temporary_contents.length = 0;
				}
				$scope.loading = false;
			});
		};

		$scope.insertRecipeEntry = function () {
			$scope.new_entry.food.id = $scope.selected.food.id;
			$scope.new_entry.food.name = $scope.selected.food.name;

			insert.recipeEntry($scope.date.sql, $scope.selected.menu.id, $scope.recipe.temporary_contents).then(function (response) {
				$scope.food_entries = response.data;
				$scope.show.popups.temporary_recipe = false;
			});
		};

		$scope.insertOrUpdateWeight = function ($keycode) {
			if ($keycode === 13) {
				weights.insertWeight($scope.date.sql).then(function (response) {
					$scope.weight = response.data;
					$scope.edit_weight = false;
					$("#weight").val("");
				});
			}
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

			deleteItem.foodEntry($entry_id, $scope.date.sql).then(function (response) {
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.show.popups.delete_food_or_recipe_entry = false;
			});
		};

		$scope.deleteRecipeEntry = function () {
			deleteItem.recipeEntry($scope.date.sql, $scope.selected.recipe.id).then(function (response) {
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.show.popups.delete_food_or_recipe_entry = false;
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
		
	}); //end controller

})();