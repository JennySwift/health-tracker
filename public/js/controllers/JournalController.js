// angular.module('tracker').controller('journal', ['$scope', function ($scope) {

// }]);


var app = angular.module('tracker');

(function () {
	app.controller('journal', function ($scope, $http) {

		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		$scope.insertOrUpdateJournalEntry = function () {
			insert.journalEntry($scope.date.sql, $scope.journal_entry.text).then(function (response) {
				$scope.journal_entry = response.data;
			});
		};
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
	}); //end controller

})();