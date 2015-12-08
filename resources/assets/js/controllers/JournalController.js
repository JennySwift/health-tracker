var app = angular.module('tracker');

(function () {
	app.controller('journal', function ($rootScope, $scope, $http, DatesFactory, JournalFactory, SleepFactory) {
		/**
		 * scope properties
		 */
		
		//journal
		$scope.journal_entry = entry;

        $scope.newSleepEntry = {
            startedYesterday: true
        };

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
            $rootScope.showLoading();
            JournalFactory.getJournalEntry($scope.date.sql)
                .then(function (response) {
                    $scope.journal_entry = response.data.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
		};

        $scope.filterJournalEntries = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $rootScope.showLoading();
            JournalFactory.filter()
                .then(function (response) {
                    $scope.filter_results = response.data.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.clearFilterResults = function () {
            $scope.filter_results = [];
            $("#filter-journal").val("");
        };

        /**
         * If the id of the journal entry exists, update the entry.
         * If not, insert the entry.
         */
		$scope.insertOrUpdateJournalEntry = function () {
            if ($scope.journal_entry.id) {
                updateEntry();
            }
            else {
                createEntry();
            }

		};

        function updateEntry () {
            $rootScope.showLoading();
            JournalFactory.update($scope.journal_entry)
                .then(function (response) {
                    $scope.journal_entry = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Entry updated');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        function createEntry () {
            $rootScope.showLoading();
            JournalFactory.insert($scope.date.sql)
                .then(function (response) {
                    $scope.journal_entry = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Entry created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        $scope.insertSleepEntry = function () {
            $rootScope.showLoading();
            SleepFactory.store($scope.newSleepEntry)
                .then(function (response) {
                    //$scope.sleeps.push(response.data);
                    $rootScope.$broadcast('provideFeedback', 'Entry created', 'success');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

		
	});

})();