var app = angular.module('tracker');

(function () {
	app.controller('journal', function ($scope, $http, DatesFactory, JournalFactory) {
		/**
		 * scope properties
		 */
		
		//journal
		$scope.journal_entry = entry;

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
			$scope.date.typed = DatesFactory.goToDate($scope.date.typed, $number);
		};

		$scope.today = function () {
			$scope.date.typed = DatesFactory.today();
		};
		$scope.changeDate = function ($keycode, $date) {
            if ($keycode !== 13) {
                return false;
            }
            var $date = $date || $("#date").val();
            $scope.date.typed = DatesFactory.changeDate($keycode, $date);
		};

		/**
		 * plugins
		 */
		
		$(".wysiwyg").wysiwyg();

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
			JournalFactory.getJournalEntry($scope.date.sql).then(function (response) {
				$scope.journal_entry = response.data;
			});
		};

        $scope.filterJournalEntries = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            JournalFactory.filter().then(function (response) {
                $scope.filter_results = response.data;
            });
        };
		
		/**
		 * insert
		 */

        /**
         * If the id of the journal entry exists, update the entry.
         * If not, insert the entry.
         */
		$scope.insertOrUpdateJournalEntry = function () {
            if ($scope.journal_entry.id) {
                JournalFactory.updateJournalEntry($scope.journal_entry).then(function (response) {
                    $scope.journal_entry = response.data;
                });
            }
            else {
                JournalFactory.insertJournalEntry($scope.date.sql).then(function (response) {
                    $scope.journal_entry = response.data;
                });
            }

		};
		
		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
	}); //end controller

})();