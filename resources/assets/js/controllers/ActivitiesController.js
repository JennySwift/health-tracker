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
    });