var $page = window.location.pathname;
var app = angular.module('tracker', ['ngSanitize', 'checklist-model']);

(function () {
	app.controller('controller', function ($scope, $http, date, select, autocomplete, quickRecipe, foods, exercises, journal, tags, units, weights) {

		/**
		 * scope properties
		 */

		//filter
		$scope.filter = {
			recipes: {
				tag_ids: []
			}
		};
		//=============tabs=============
		$scope.tab = {
			food_entries: true
		};

		//autocomplete
		$scope.autocomplete_options = {
			exercises: {},
			menu_items: {},
			foods: {},
			temporary_recipe_foods: {}
		};

		//show
		$scope.show = {
			autocomplete_options: {
				exercises: false,
				menu_items: false,
				foods: false,
				temporary_recipe_foods: false
			},
			popups: {
				recipe: false,
				similar_names: false,
				temporary_recipe: false,
				food_info: false,
				exercise: false,
				exercise_entries: false,
				exercise_series_history: false
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

		//new entry
		$scope.new_entry = {
			exercise: {
				unit: {}
			},
			exercise_unit: {},
			menu: {},
			food: {},
		};
		

		//edit
		$scope.edit = {
			recipe_method: false
		};
		
		//other
		$scope.date = {};
		$scope.loading = false;
		$scope.errors = {};

		$scope.units = {
			food: {},
			exercise: {}
		};
		$scope.unit_id = ""; //for the select element in the recipe popup

		//associated food units
		$scope.all_foods_with_units = {};//all foods, with their associated units and default unit		
		$scope.assoc_units = {};//associated units for one chosen food. This is made from $scope.all_foods_with_units.
		$scope.food_and_assoc_units_array = {};//for the food popup, with checked state and calorie info. Associated units of one food. I could probably combine this info all into $scope.all_foods_with_units and get rid of this.


		/**
		 * functions
		 */
		
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

		$scope.changeTab = function ($tab) {
			$scope.tab = {};
			$scope.tab[$tab] = true;
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

		
		

		// ===========================page load===========================

		$scope.pageLoad = function () {
			select.pageLoad($scope.date.sql).then(function (response) {
				$scope.foods = response.data.foods;
				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
				$scope.exercise_entries = response.data.exercise_entries;
				$scope.recipes.filtered = response.data.recipes;
				$scope.units.food = response.data.food_units;
				$scope.units.exercise = response.data.exercise_units;
				$scope.all_foods_with_units = response.data.foods_with_units;
				$scope.weight = response.data.weight;
				$scope.units.exercise = response.data.exercise_units;
				$scope.exercises = response.data.exercises;
				$scope.exercise_series = response.data.exercise_series;
				// $scope.getMenu($scope.foods, $scope.recipes);
				$scope.journal_entry = response.data.journal_entry;
				$scope.exercise_tags = response.data.exercise_tags;
				$scope.workouts = response.data.workouts;
				$scope.recipe_tags = response.data.recipe_tags;

				$scope.selected.exercise_unit.id = $scope.units.exercise[0].id;
			});
		};

		// ===========================entries===========================

		$scope.getEntries = function () {
			select.getEntries($scope.date.sql).then(function (response) {
				$scope.weight = response.data.weight;
				$scope.exercise_entries = response.data.exercise_entries;
				$scope.journal_entry = response.data.journal_entry;	

				$scope.food_entries = response.data.food_entries;
				$scope.calories.day = response.data.calories_for_the_day;
				$scope.calories.week_avg = response.data.calories_for_the_week;
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
		 * media queries
		 */

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

		/**
		 * other
		 */

		$scope.closePopup = function ($event, $popup) {
			var $target = $event.target;
			if ($target.className === 'popup-outer') {
				$scope.show.popups[$popup] = false;
			}
			console.log('something');
		};
		
	}); //end display controller

})();