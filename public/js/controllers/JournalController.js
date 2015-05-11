// angular.module('tracker').controller('journal', ['$scope', function ($scope) {

// }]);


var app = angular.module('tracker');

(function () {
	app.controller('journal', function ($scope, $http, date, journal) {
		/**
		 * scope properties
		 */
		
		//journal
		$scope.journal_entry = {};
		
		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		$scope.insertOrUpdateJournalEntry = function () {
			journal.insertJournalEntry($scope.date.sql, $scope.journal_entry.text).then(function (response) {
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