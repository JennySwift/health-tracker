var app = angular.module('tracker');

(function () {
	app.controller('units', function ($scope, $http, UnitsFactory) {

		/**
		 * scope properties
		 */
		
		$scope.units = units;

		/**
		 * select
		 */

		/**
		 * insert
		 */
		
		$scope.insertFoodUnit = function ($keycode) {
			if ($keycode === 13) {
				UnitsFactory.insertFoodUnit().then(function (response) {
					$scope.units.food = response.data;
				});
			}
		};
		
		$scope.insertExerciseUnit = function ($keycode) {
			if ($keycode === 13) {
				UnitsFactory.insertExerciseUnit().then(function (response) {
					$scope.units.exercise = response.data;
				});
			}
		};

		/**
		 * update
		 */

		/**
		 * delete
		 */
		
		$scope.deleteExerciseUnit = function ($id) {
			UnitsFactory.deleteExerciseUnit($id).then(function (response) {
				$scope.units.exercise = response.data;
			});
		};

		$scope.deleteFoodUnit = function ($id) {
			UnitsFactory.deleteFoodUnit($id).then(function (response) {
				$scope.units.food = response.data;
			});
		};
		
	}); //end controller

})();