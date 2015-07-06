var app = angular.module('tracker');

(function () {
    app.controller('workouts', function ($scope, $http, ExercisesFactory, TagsFactory) {

        /**
         * scope properties
         */

        $scope.workouts = workouts;

        /**
         * select
         */

        /**
         * insert
         */

        $scope.insertWorkout = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            ExercisesFactory.insertWorkout().then(function (response) {
                $scope.workouts = response.data;
            });
        };

        $scope.insertSeriesIntoWorkout = function () {
            ExercisesFactory.insertSeriesIntoWorkout($workout_id, $series_id).then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        /**
         * update
         */

        /**
         * delete
         */

        /**
         * popups
         */

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    }); //end controller

})();