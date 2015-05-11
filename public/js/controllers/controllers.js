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

		//tabs
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
		
		$scope.loading = false;
		$scope.errors = {};

		$scope.units = {
			food: {},
			exercise: {}
		};
		$scope.unit_id = ""; //for the select element in the recipe popup

		/**
		 * functions
		 */

		$scope.changeTab = function ($tab) {
			$scope.tab = {};
			$scope.tab[$tab] = true;
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
		 * plugins
		 */
		
		$(".wysiwyg").wysiwyg();

		/**
		 * media queries
		 */

		 /**
		  * $scope.apply works how I want it but it keeps causing a firebug error
		  */

		enquire.register("screen and (min-width: 600px", {
			match: function () {
				// $("body").css('background', blue);
				if ($scope.tab.food_entries || $scope.tab.exercise_entries) {
					$scope.changeTab('entries');
					// $scope.$apply();
				}
			},
			unmatch: function () {
				if ($scope.tab.entries) {
					$scope.changeTab('food_entries');
					// $scope.$apply();
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
		
	}); //end controller

})();