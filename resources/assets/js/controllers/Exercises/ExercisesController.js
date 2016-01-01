var app = angular.module('tracker');

(function () {
    app.controller('exercises', function ($rootScope, $scope, $http, ExercisesFactory, ExerciseSeriesFactory, ProgramsFactory) {

        $scope.exercises = all_exercises;
        $scope.exercise_entries = {};
        $scope.exercise_series = series;
        $scope.workouts = workouts;
        $scope.exercise_tags = exercise_tags;
        $scope.units = units;
        $scope.showNewExerciseFields = false;

        //show
        $scope.show = {
            autocomplete_options: {
                exercises: false,
            },
            popups: {
                exercise: false,
                exercise_entries: false,
                exercise_series_history: false
            }
        };

        //selected
        $scope.selected = {};

        function getSeries () {
            $rootScope.showLoading();
            ExerciseSeriesFactory.index()
                .then(function (response) {
                    $scope.series = _.pluck(response.data, 'name');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getSeries();

        $scope.insertExercise = function ($keycode) {
            if ($keycode === 13) {
                $rootScope.showLoading();
                ExercisesFactory.insert($scope.newExercise)
                    .then(function (response) {
                        $scope.exercises.push(response.data);
                        $rootScope.$broadcast('provideFeedback', 'Exercise created');
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
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

        $scope.updateExercise = function () {
            $rootScope.showLoading();
            ExercisesFactory.update($scope.exercise_popup)
                .then(function (response) {
                    $scope.exercise_popup = response.data.data;
                    $rootScope.$broadcast('provideFeedback', 'Exercise updated');
                    var $index = _.indexOf($scope.exercises, _.findWhere($scope.exercises, {id: $scope.exercise_popup.id}));
                    $scope.exercises[$index] = response.data.data;
                    $scope.show.popups.exercise = false;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.deleteExercise = function ($exercise) {
            $rootScope.showLoading();
            ExercisesFactory.destroy($exercise)
                .then(function (response) {
                    $scope.exercises = _.without($scope.exercises, $exercise);
                    $rootScope.$broadcast('provideFeedback', 'Exercise deleted');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * popups
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

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    });

})();