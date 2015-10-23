var app = angular.module('tracker');

(function () {
    app.controller('exercises', function ($rootScope, $scope, $http, ExercisesFactory) {

        $scope.exercises = all_exercises;
        $scope.exercise_entries = {};
        $scope.exercise_series = series;
        $scope.workouts = workouts;
        $scope.exercise_tags = exercise_tags;
        $scope.units = units;

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

        $scope.insertExercise = function ($keycode) {
            if ($keycode === 13) {
                //$rootScope.showLoading();
                ExercisesFactory.insert()
                    .then(function (response) {
                        $scope.exercises.push(response.data);
                        $rootScope.$broadcast('provideFeedback', 'Exercise created');
                        //$rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        };

        $scope.updateExercise = function () {
            ExercisesFactory.updateExercise($scope.exercise_popup.exercise).then(function (response) {
                //$scope.exercises = response.data;

                //deletes tags from the exercise then adds the correct ones
                ExercisesFactory.insertTagsInExercise($scope.selected.exercise.id, $scope.exercise_popup.tags).then(function (response) {
                    $scope.exercises = response.data;
                    $scope.show.popups.exercise = false;
                });
            });
        };

        $scope.deleteExercise = function ($exercise) {
            //$rootScope.showLoading();
            ExercisesFactory.destroy($exercise)
                .then(function (response) {
                    $scope.exercises = _.without($scope.exercises, $exercise);
                    $rootScope.$broadcast('provideFeedback', 'Execise deleted');
                    //$rootScope.hideLoading();
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

            ExercisesFactory.getExerciseInfo($exercise).then(function (response) {
                $scope.exercise_popup = response.data;
                $scope.show.popups.exercise = true;
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