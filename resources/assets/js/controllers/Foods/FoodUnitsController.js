angular.module('tracker')
    .controller('FoodUnitsController', function ($scope) {
        $scope.units = units;


        $scope.insertFoodUnit = function ($keycode) {
            if ($keycode === 13) {
                FoodUnitsFactory.insertFoodUnit().then(function (response) {
                    $scope.units.food = response.data;
                });
            }
        };

        $scope.deleteFoodUnit = function ($id) {
            FoodUnitsFactory.deleteFoodUnit($id).then(function (response) {
                $scope.units.food = response.data;
            });
        };
    });