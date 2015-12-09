angular.module('tracker')
    .controller('TimersController', function ($rootScope, $scope, TimersFactory) {

        function getEntries () {
            $rootScope.showLoading();
            TimersFactory.getEntries()
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