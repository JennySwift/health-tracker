var app = angular.module('tracker');

(function () {
	app.controller('exercises', function ($scope, $http, ExercisesFactory, TagsFactory) {

		/**
		 * scope properties
		 */
		
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
		
		/**
		 * select
		 */

		/**
		 * insert
		 */

		$scope.insertExercise = function ($keycode) {
			if ($keycode === 13) {
				ExercisesFactory.insertExercise().then(function (response) {
					$scope.exercises = response.data;
				});
			}
		};
		
		/**
		 * update
		 */

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

		/**
		 * delete
		 */

		$scope.deleteExercise = function ($exercise) {
			ExercisesFactory.deleteExercise($exercise).then(function (response) {
				$scope.exercises = response.data;
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
		
	}); //end controller

})();