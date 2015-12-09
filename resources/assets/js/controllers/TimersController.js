angular.module('tracker')
    .controller('TimersController', function ($timeout, $rootScope, $scope, TimersFactory, ActivitiesFactory) {

        //$("document").ready(function () {
        //    $("#new-timer-activity").select2({});
        //});
        //
        //$scope.newTimer = {
        //    activity: {}
        //};

        //$timeout(function () {
        //    $("#new-timer-activity").select2({});
        //});

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

        $scope.test = function () {
            console.log('eh?');
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
                    $scope.timerInProgress = false;
                    //var $index = _.indexOf($scope.timers, _.findWhere($scope.timers, {id: response.data.id}));
                    //$scope.timers[$index] = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Timer updated');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        function getTimers () {
            $rootScope.showLoading();
            TimersFactory.index()
                .then(function (response) {
                    $scope.timers = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getTimers();

        $scope.filterTimers = function (timer) {
            if ($scope.timersFilter) {
                return timer.activity.data.name.indexOf($scope.timersFilter) !== -1;
            }
            return true;

        };

        $scope.formatMinutes = function (minutes) {
            return minutes * 10;
        };

    });