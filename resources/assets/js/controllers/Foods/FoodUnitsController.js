angular.module('tracker')
    .controller('FoodUnitsController', function ($scope, $rootScope, FoodUnitsFactory) {
        $scope.units = units;

        $scope.insertFoodUnit = function ($keycode) {
            if ($keycode === 13) {
                //$scope.showLoading();
                FoodUnitsFactory.insert()
                    .then(function (response) {
                        $scope.units.push(response.data.data);
                        $rootScope.$broadcast('provideFeedback', 'Unit created');
                        //$scope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        };

        //Todo
        $scope.updateFoodUnit = function () {

        };

        $scope.deleteFoodUnit = function ($unit) {
            //$scope.showLoading();
            FoodUnitsFactory.destroy($unit)
                .then(function (response) {
                    $scope.units = _.without($scope.units, $unit);
                    $rootScope.$broadcast('provideFeedback', 'Unit deleted');
                    //$scope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };
    });