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

        $scope.date = {};

        $scope.$on('changeDate', function (event) {
            getTimers();
            getTotalMinutesForActivitiesForTheDay();
        });

        $scope.startTimer = function () {
            $('#timer-clock').timer({format: '%H:%M:%S'});
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
            $('#timer-clock').timer('remove');
            $rootScope.showLoading();
            TimersFactory.update($scope.timerInProgress)
                .then(function (response) {
                    $scope.timerInProgress = false;
                    $scope.timers.push(response.data);
                    getTotalMinutesForActivitiesForTheDay();
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
            TimersFactory.index(false, $scope.date.sql)
                .then(function (response) {
                    $scope.timers = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        $scope.filterTimers = function (timer) {
            if ($scope.timersFilter) {
                return timer.activity.data.name.indexOf($scope.timersFilter) !== -1;
            }
            return true;

        };

        $scope.formatMinutes = function (minutes) {
            return minutes * 10;
        };

        function checkForTimerInProgress () {
            $rootScope.showLoading();
            TimersFactory.checkForTimerInProgress()
                .then(function (response) {
                    if (response.data.length > 0) {
                        resumeTimerOnPageLoad(response.data);
                    }

                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        checkForTimerInProgress();

        function resumeTimerOnPageLoad (timer) {
            $scope.timerInProgress = timer;
            var seconds = moment().diff(moment(timer.start, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
            $('#timer-clock').timer({
                format: '%H:%M:%S',
                //The timer has already started
                seconds: seconds
            });
        }

        function getTotalMinutesForActivitiesForTheDay () {
            $rootScope.showLoading();
            ActivitiesFactory.getTotalMinutesForDay($scope.date.sql)
                .then(function (response) {
                    $scope.activitiesWithDurations = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        $scope.deleteTimer = function (timer) {
            if (confirm("Are you sure?")) {
                $rootScope.showLoading();
                TimersFactory.destroy(timer)
                    .then(function (response) {
                        $scope.timers = _.without($scope.timers, timer);
                        getTotalMinutesForActivitiesForTheDay();
                        $rootScope.$broadcast('provideFeedback', 'Timer deleted');
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        };

    });