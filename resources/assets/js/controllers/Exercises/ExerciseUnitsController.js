angular.module('tracker')
    .controller('ExerciseUnitsController', function ($rootScope, $scope, ExerciseUnitsFactory) {

        $scope.units = units;

        $scope.insertExerciseUnit = function ($keycode) {
            if ($keycode === 13) {
                //$scope.showLoading();
                ExerciseUnitsFactory.insert()
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
        $scope.updateExerciseUnit = function () {

        };

        $scope.deleteExerciseUnit = function ($unit) {
            //$scope.showLoading();
            ExerciseUnitsFactory.destroy($unit)
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