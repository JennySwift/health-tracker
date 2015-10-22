angular.module('tracker')
    .controller('FoodUnitsController', function ($scope, $rootScope, FoodUnitsFactory) {
        $scope.units = units;

        $scope.insertFoodUnit = function ($keycode) {
            if ($keycode === 13) {
                FoodUnitsFactory.insert().then(function (response) {
                    $scope.units.food = response.data;
                });
            }
        };

        //Todo
        $scope.updateFoodUnit = function () {

        };

        $scope.deleteFoodUnit = function ($id) {
            //$scope.showLoading();
            FoodUnitsFactory.destroy($id)
                .then(function (response) {
                    //$scope.units.food = response.data;
                    $rootScope.$broadcast('provideFeedback', 'Unit deleted');
                    //$scope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };
    });