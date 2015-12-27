var app = angular.module('tracker');

(function () {
    app.controller('SeriesController', function ($rootScope, $scope, $http, ExercisesFactory, WorkoutsFactory, ExerciseSeriesFactory, ExerciseUnitsFactory, ExerciseEntriesFactory, ProgramsFactory) {

        /**
         * scope properties
         */

        $scope.exercise_series = series;
        $scope.workouts = workouts;
        $scope.seriesPriorityFilter = 1;

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
            ExerciseSeriesFactory.insert($scope.newSeries)
                .then(function (response) {
                    $scope.exercise_series.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Series created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.insertExerciseSet = function ($exercise) {
            $rootScope.showLoading();
            ExerciseEntriesFactory.insertExerciseSet(moment().format('YYYY-MM-DD'), $exercise)
                .then(function (response) {
                    $rootScope.$broadcast('getExerciseEntries', response.data);
                    //$rootScope.$broadcast('provideFeedback', '');
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
                    $scope.exercise_series[$index] = response.data;
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

        $scope.getExercisesInSeries = function ($series) {
            //$scope.selected.exercise_series = $series;

            $rootScope.showLoading();
            ExerciseSeriesFactory.getExerciseSeriesInfo($series)
                .then(function (response) {
                    $scope.selected.exercise_series = response.data;

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
                    $scope.exercise_series_popup = response.data;
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

        /**
         * Duplicate from exercises controller
         * @param $exercise
         */
        $scope.showExercisePopup = function ($exercise) {
            $scope.selected.exercise = $exercise;

            $rootScope.showLoading();
            ExercisesFactory.show($exercise)
                .then(function (response) {
                    $scope.exercise_popup = response.data;
                    $scope.show.popups.exercise = true;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * Almost duplicate from exercises controller
         */
        $scope.updateExercise = function () {
            $rootScope.showLoading();
            ExercisesFactory.update($scope.exercise_popup)
                .then(function (response) {
                    $scope.exercise_popup = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Exercise updated');
                    var $index = _.indexOf($scope.selected.exercise_series.exercises.data, _.findWhere($scope.selected.exercise_series.exercises.data, {id: $scope.exercise_popup.id}));
                    $scope.selected.exercise_series.exercises.data[$index] = response.data.data;
                    $scope.show.popups.exercise = false;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        function getPrograms () {
            $rootScope.showLoading();
            ProgramsFactory.index()
                .then(function (response) {
                    $scope.programs = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getPrograms();

        function getUnits () {
            $rootScope.showLoading();
            ExerciseUnitsFactory.index()
                .then(function (response) {
                    $scope.units = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getUnits();

    });

})();