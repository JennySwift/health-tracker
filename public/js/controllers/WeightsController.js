var app = angular.module('tracker');

(function () {
	app.controller('weights', function ($scope, $http, date, select, autocomplete, quickRecipe, foods, exercises, journal, tags, units, weights) {

		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		$scope.insertOrUpdateWeight = function ($keycode) {
			if ($keycode === 13) {
				insert.weight($scope.date.sql).then(function (response) {
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