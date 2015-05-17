var app = angular.module('tracker');

(function () {
	app.controller('journal', function ($scope, $http, date, journal) {
		/**
		 * scope properties
		 */
		
		//journal
		$scope.journal_entry = {};

		//date
		/**
		 * There is a lot of date stuff here that is duplication of the date stuff in EntriesController.js.
		 * Any way of making it dry?
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

		/**
		 * watches
		 */
		
		$scope.$watch('date.typed', function (newValue, oldValue) {
			$scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
			$scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
			$("#date").val(newValue);

			if (newValue === oldValue) {
				// $scope.pageLoad();
			}
			else {
				$scope.getJournalEntry();
			}
		});
		
		/**
		 * select
		 */
		
		$scope.getJournalEntry = function () {
			journal.getJournalEntry($scope.date.sql).then(function (response) {
				$scope.journal_entry = response.data;
			});
		};
		
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