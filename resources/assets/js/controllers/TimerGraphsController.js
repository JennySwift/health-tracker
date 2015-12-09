angular.module('tracker')
    .controller('TimerGraphsController', function ($rootScope, $scope, TimersFactory) {

        function getEntries () {
            $rootScope.showLoading();
            TimersFactory.index(true)
                .then(function (response) {
                    $scope.entries = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getEntries();
    });