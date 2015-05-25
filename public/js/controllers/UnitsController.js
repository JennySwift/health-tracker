var app = angular.module('tracker');

(function () {
	app.controller('units', function ($scope, $http, units) {

		/**
		 * scope properties
		 */
		
		$scope.units = {
			food: [],
			exercise: []
		};

		$scope.unit_id = ""; //for the select element in the recipe popup

		/**
		 * select
		 */
		
		$scope.getAllUnits = function () {
			units.getAllUnits().then(function (response) {
				$scope.units = response.data;
			});
		};

		$scope.getAllUnits();

		/**
		 * insert
		 */
		
		$scope.insertFoodUnit = function ($keycode) {
			if ($keycode === 13) {
				units.insertFoodUnit().then(function (response) {
					$scope.units.food = response.data;
				});
			}
		};
		
		$scope.insertExerciseUnit = function ($keycode) {
			if ($keycode === 13) {
				units.insertExerciseUnit().then(function (response) {
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
			units.deleteExerciseUnit($id).then(function (response) {
				$scope.units.exercise = response.data;
			});
		};

		$scope.deleteFoodUnit = function ($id) {
			units.deleteFoodUnit($id).then(function (response) {
				$scope.units.food = response.data;
			});
		};
		
	}); //end controller

})();