angular.module('tracker')
    .controller('ActivitiesController', function ($rootScope, $scope, ActivitiesFactory) {

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

        $scope.insertActivity = function () {
            $rootScope.showLoading();
            ActivitiesFactory.store($scope.newActivity)
                .then(function (response) {
                    $scope.activities.push(response.data);
                    $rootScope.$broadcast('provideFeedback', 'Activity created', 'success');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.updateActivity = function (activity) {
            $rootScope.showLoading();
            ActivitiesFactory.update(activity)
                .then(function (response) {
                    var $index = _.indexOf($scope.activities, _.findWhere($scope.activities, {id: activity.id}));
                    $scope.activities[$index] = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Activity updated');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.deleteActivity = function (activity) {
            if (confirm("Are you sure? The timers for the activity will be deleted, too!")) {
                $rootScope.showLoading();
                ActivitiesFactory.destroy(activity)
                    .then(function (response) {
                        $scope.activities = _.without($scope.activities, activity);
                        $rootScope.$broadcast('provideFeedback', 'Activity deleted');
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        };
    });