var app = angular.module('tracker');

(function () {
	app.controller('dates', function ($scope, $http, date, select) {

		/**
		 * scope properties
		 */
		
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
				$scope.pageLoad();
			}
			else {
				$scope.getEntries();
			}
		});
		
		/**
		 * select
		 */
		
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

		/**
		 * insert
		 */
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
		
	}); //end controller

})();