angular.module('tracker')
    .controller('SleepController', function ($rootScope, $scope, SleepFactory) {

        function getEntries () {
            $rootScope.showLoading();
            SleepFactory.getEntries()
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