var app = angular.module('tracker');

(function () {
    app.controller('SeriesController', function ($scope, $http, ExercisesFactory, TagsFactory) {

        /**
         * scope properties
         */

        $scope.exercise_series = series;
        $scope.workouts = workouts;

        //show
        $scope.show = {
            popups: {
                exercise_series_history: false
            }
        };

        $scope.selected = {
            exercise_series: {}
        };

        /**
         * select
         */

        $scope.getExerciseSeriesHistory = function ($series_id) {
            ExercisesFactory.getExerciseSeriesHistory($series_id).then(function (response) {
                $scope.show.popups.exercise_series_history = true;
                $scope.exercise_series_history = response.data;
            });
        };

        /**
         * insert
         */

        $scope.insertExerciseSeries = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            ExercisesFactory.insertExerciseSeries().then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        //$scope.insertSeriesIntoWorkout = function () {
        //    ExercisesFactory.insertSeriesIntoWorkout($workout_id, $series_id).then(function (response) {
        //        $scope.exercise_series = response.data;
        //    });
        //};

        /**
         * update
         */

        /**
         * delete
         */

        $scope.deleteAndInsertSeriesIntoWorkouts = function () {
            ExercisesFactory.deleteAndInsertSeriesIntoWorkouts($scope.selected.exercise_series.id, $scope.exercise_series_popup.workouts).then(function (response) {
                $scope.workouts = response.data;
                $scope.show.popups.exercise_series = false;
            });
        };

        $scope.deleteExerciseSeries = function ($series) {
            ExercisesFactory.deleteExerciseSeries($series).then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        /**
         * popups
         */

        $scope.showExerciseSeriesPopup = function ($series) {
            $scope.selected.exercise_series = $series;

            ExercisesFactory.getExerciseSeriesInfo($series).then(function (response) {
                $scope.exercise_series_popup = response.data;
                $scope.show.popups.exercise_series = true;
            });
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    }); //end controller

})();