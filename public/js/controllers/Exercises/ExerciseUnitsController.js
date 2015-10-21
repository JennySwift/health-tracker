angular.module('tracker')
    .controller('ExerciseUnitsController', function ($scope, ExerciseUnitsFactory) {

        $scope.units = units;

        $scope.insertExerciseUnit = function ($keycode) {
            if ($keycode === 13) {
                ExerciseUnitsFactory.insertExerciseUnit().then(function (response) {
                    $scope.units.exercise = response.data;
                });
            }
        };

        $scope.deleteExerciseUnit = function ($id) {
            ExerciseUnitsFactory.deleteExerciseUnit($id).then(function (response) {
                $scope.units.exercise = response.data;
            });
        };
    });