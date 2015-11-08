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

        $scope.insertExerciseSeries = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            $rootScope.showLoading();
            ExerciseSeriesFactory.insert()
                .then(function (response) {
                    $scope.exercise_series.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Series created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.updateSeries = function () {
            $rootScope.showLoading();
            ExerciseSeriesFactory.update($scope.exercise_series_popup)
                .then(function (response) {
                    var $index = _.indexOf($scope.exercise_series, _.findWhere($scope.exercise_series, {id: $scope.exercise_series_popup.id}));
                    $scope.exercise_series[$index] = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Series updated');
                    $scope.show.popups.exercise_series = false;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.deleteExerciseSeries = function ($series) {
            $rootScope.showLoading();
            ExerciseSeriesFactory.destroy($series)
                .then(function (response) {
                    $scope.exercise_series = _.without($scope.exercise_series, $series);
                    $rootScope.$broadcast('provideFeedback', 'Series deleted');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
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