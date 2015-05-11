var app = angular.module('tracker');

(function () {
	app.controller('weights', function ($scope, $http, date, weights) {
		/**
		 * scope properties
		 */
		
		$scope.weight = "";
		$scope.edit_weight = false;

		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
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
		
	}); //end controller

})();