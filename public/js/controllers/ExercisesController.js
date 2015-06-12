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
		
		$scope.getExerciseSeriesHistory = function ($series_id) {
			ExercisesFactory.getExerciseSeriesHistory($series_id).then(function (response) {
				$scope.show.popups.exercise_series_history = true;
				$scope.exercise_series_history = response.data;
			});
		};

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
		
		$scope.insertExerciseSeries = function ($keypress) {
			if ($keypress !== 13) {
				return;
			}
			ExercisesFactory.insertExerciseSeries().then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

		$scope.insertSeriesIntoWorkout = function () {
			ExercisesFactory.insertSeriesIntoWorkout($workout_id, $series_id).then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

		$scope.insertExerciseTag = function ($keypress) {
			if ($keypress !== 13) {
				return;
			}
			TagsFactory.insertExerciseTag().then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.insertTagsInExercise = function () {
			//deletes tags from the exercise then adds the correct ones
			ExercisesFactory.insertTagsInExercise($scope.selected.exercise.id, $scope.exercise_popup.tags).then(function (response) {
				$scope.exercises = response.data;
				$scope.show.popups.exercise = false;
			});
		};

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
		
		$scope.updateDefaultExerciseQuantity = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			ExercisesFactory.updateDefaultExerciseQuantity($scope.selected.exercise.id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateExerciseSeries = function ($exercise_id, $series_id) {
			//to assign a series to an exercise
			ExercisesFactory.updateExerciseSeries($exercise_id, $series_id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateExerciseStepNumber = function ($keycode, $exercise_id) {
			if ($keycode !== 13) {
				return;
			}
			ExercisesFactory.updateExerciseStepNumber($exercise_id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateDefaultExerciseUnit = function ($unit_id) {
			ExercisesFactory.updateDefaultExerciseUnit($scope.selected.exercise.id, $unit_id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		/**
		 * delete
		 */
		
		$scope.deleteAndInsertSeriesIntoWorkouts = function () {
			ExercisesFactory.deleteAndInsertSeriesIntoWorkouts($scope.selected.exercise_series.id, $scope.exercise_series_popup.workouts).then(function (response) {
				// $scope.exercise_series = response.data;
				$scope.workouts = response.data;
				$scope.show.popups.exercise_series = false;
			});
		};

		$scope.deleteExerciseTag = function ($id) {
			TagsFactory.deleteExerciseTag($id).then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.deleteExerciseSeries = function ($id) {
			ExercisesFactory.deleteExerciseSeries($id).then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

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

			ExercisesFactory.getExerciseInfo($exercise.id).then(function (response) {
				$scope.exercise_popup = response.data;
				$scope.show.popups.exercise = true;
			});
		};

		$scope.showExerciseSeriesPopup = function ($series) {
			$scope.selected.exercise_series = $series;

			ExercisesFactory.getExerciseSeriesInfo($series.id).then(function (response) {
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