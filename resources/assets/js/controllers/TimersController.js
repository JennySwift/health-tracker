angular.module('tracker')
    .controller('TimersController', function ($rootScope, $scope, TimersFactory, ActivitiesFactory) {

        $scope.startTimer = function () {
            $rootScope.showLoading();
            TimersFactory.store($scope.newTimer)
                .then(function (response) {
                    //$scope.timers.push(response.data);
                    $scope.timerInProgress = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Timer started', 'success');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        function getActivities () {
            $rootScope.showLoading();
            ActivitiesFactory.index()
                .then(function (response) {
                    $scope.activities = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getActivities();

        $scope.stopTimer = function () {
            $rootScope.showLoading();
            TimersFactory.update($scope.timerInProgress)
                .then(function (response) {
                    //var $index = _.indexOf($scope.timers, _.findWhere($scope.timers, {id: response.data.id}));
                    //$scope.timers[$index] = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Timer updated');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

    });