var app = angular.module('tracker');

(function () {
    app.controller('SeriesController', function ($rootScope, $scope, $http, ExercisesFactory, WorkoutsFactory, ExerciseSeriesFactory) {

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

        $scope.getExerciseSeriesHistory = function ($series) {
            $rootScope.showLoading();
            ExerciseSeriesFactory.getExerciseSeriesHistory($series)
                .then(function (response) {
                    $scope.show.popups.exercise_series_history = true;
                    //For displaying the name of the series in the popup
                    $scope.selectedSeries = $series;
                    $scope.exercise_series_history = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * insert
         */

        $scope.insertExerciseSeries = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            ExercisesSeriesFactory.insertExerciseSeries().then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        $scope.deleteAndInsertSeriesIntoWorkouts = function () {
            WorkoutsFactory.deleteAndInsertSeriesIntoWorkouts($scope.selected.exercise_series.id, $scope.exercise_series_popup.workouts).then(function (response) {
                $scope.workouts = response.data;
                $scope.show.popups.exercise_series = false;
            });
        };

        $scope.deleteExerciseSeries = function ($series) {
            ExercisesSeriesFactory.deleteExerciseSeries($series).then(function (response) {
                $scope.exercise_series = response.data;
            });
        };

        /**
         * popups
         */

        $scope.showExerciseSeriesPopup = function ($series) {
            $scope.selected.exercise_series = $series;

            $rootScope.showLoading();
            ExerciseSeriesFactory.getExerciseSeriesInfo($series)
                .then(function (response) {
                    $scope.exercise_series_popup = response.data.data;
                    $scope.show.popups.exercise_series = true;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    });

})();