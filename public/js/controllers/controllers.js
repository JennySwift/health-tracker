var $page = window.location.pathname;
var app = angular.module('tracker', ['ngSanitize', 'checklist-model']);

(function () {
	app.controller('controller', function ($scope, $http, date, select, autocomplete, quickRecipe, foods, exercises, journal, tags, units, weights) {

		 /**
		  * media queries
		  * $scope.apply works how I want it but it keeps causing a firebug error
		  */

		enquire.register("screen and (min-width: 600px", {
			match: function () {
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
	
		
	}); //end controller

})();