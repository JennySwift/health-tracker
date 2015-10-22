var app = angular.module('tracker');

(function () {
    app.controller('workouts', function ($scope, $http, ExercisesFactory, WorkoutsFactory) {

        $scope.workouts = workouts;

        $scope.insertWorkout = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            WorkoutsFactory.insertWorkout().then(function (response) {
                $scope.workouts = response.data;
            });
        };

        $scope.insertSeriesIntoWorkout = function () {
            WorkoutsFactory.insertSeriesIntoWorkout($workout_id, $series_id).then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        /**
         * popups
         */

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    });

})();