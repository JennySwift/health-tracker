var app = angular.module('tracker');

(function () {
	app.controller('exercises', function ($scope, $http, exercises, tags) {

		/**
		 * scope properties
		 */
		
		$scope.exercises = {};
		$scope.exercise_entries = {};
		
		/**
		 * select
		 */
		
		$scope.getExercises = function () {
			exercises.getExercises().then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.getExercises();


		$scope.getExerciseSeriesHistory = function ($series_id) {
			select.exerciseSeriesHistory($series_id).then(function (response) {
				$scope.show.popups.exercise_series_history = true;
				$scope.exercise_series_history = response.data;
			});
		};

		 $scope.getSpecificExerciseEntries = function ($exercise_id, $exercise_unit_id) {
		 	select.specificExerciseEntries($scope.date.sql, $exercise_id, $exercise_unit_id).then(function (response) {
		 		$scope.show.popups.exercise_entries = true;
		 		$scope.specific_exercise_entries = response.data;
		 	});
		 };

		/**
		 * insert
		 */
		
		$scope.insertExerciseSeries = function ($keypress) {
			if ($keypress !== 13) {
				return;
			}
			insert.exerciseSeries().then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

		$scope.insertSeriesIntoWorkout = function () {
			insert.seriesIntoWorkout($workout_id, $series_id).then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

		$scope.insertExerciseTag = function ($keypress) {
			if ($keypress !== 13) {
				return;
			}
			insert.exerciseTag().then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.insertTagsInExercise = function () {
			//deletes tags from the exercise then adds the correct ones
			insert.tagsInExercise($scope.selected.exercise.id, $scope.selected.exercise.tags).then(function (response) {
				$scope.exercises = response.data;
				$scope.show.popups.exercise = false;
			});
		};

		$scope.insertExercise = function ($keycode) {
			if ($keycode === 13) {
				insert.exercise().then(function (response) {
					$scope.exercises = response.data;
				});
			}
		};

		$scope.insertExerciseEntry = function () {
			$scope.new_entry.exercise.unit_id = $scope.selected.exercise_unit.id;
			insert.exerciseEntry($scope.date.sql, $scope.new_entry.exercise).then(function (response) {
				$scope.exercise_entries = response.data;
			});
		};

		$scope.insertExerciseSet = function ($exercise_id) {
			insert.exerciseSet($scope.date.sql, $exercise_id).then(function (response) {
				$scope.exercise_entries = response.data;
			});
		};
		
		/**
		 * update
		 */
		
		$scope.updateDefaultExerciseQuantity = function ($keycode) {
			if ($keycode !== 13) {
				return;
			}
			update.defaultExerciseQuantity($scope.selected.exercise.id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateExerciseSeries = function ($exercise_id, $series_id) {
			//to assign a series to an exercise
			update.exerciseSeries($exercise_id, $series_id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateExerciseStepNumber = function ($keycode, $exercise_id) {
			if ($keycode !== 13) {
				return;
			}
			update.exerciseStepNumber($exercise_id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.updateDefaultExerciseUnit = function ($unit_id) {
			update.defaultExerciseUnit($scope.selected.exercise.id, $unit_id).then(function (response) {
				$scope.exercises = response.data;
				$scope.show.popups.exercise = false;
			});
		};

		/**
		 * delete
		 */
		
		$scope.deleteAndInsertSeriesIntoWorkouts = function () {
			insert.deleteAndInsertSeriesIntoWorkouts($scope.selected.exercise_series.id, $scope.selected.exercise_series.workouts).then(function (response) {
				$scope.exercise_series = response.data;
				$scope.show.popups.exercise_series = false;
			});
		};

		$scope.deleteExerciseTag = function ($id) {
			deleteItem.exerciseTag($id).then(function (response) {
				$scope.exercise_tags = response.data;
			});
		};

		$scope.deleteExerciseSeries = function ($id) {
			deleteItem.exerciseSeries($id).then(function (response) {
				$scope.exercise_series = response.data;
			});
		};

		$scope.deleteExercise = function ($id) {
			deleteItem.exercise($id).then(function (response) {
				$scope.exercises = response.data;
			});
		};

		$scope.deleteExerciseEntry = function ($id) {
			deleteItem.exerciseEntry($id, $scope.date.sql).then(function (response) {
				$scope.exercise_entries = response.data;
			});
		};
		
		/**
		 * popups
		 */
		
		$scope.showExercisePopup = function ($exercise) {
			$scope.selected.exercise = $exercise;
			$scope.show.popups.exercise = true;
		};

		$scope.showExerciseSeriesPopup = function ($series) {
			$scope.selected.exercise_series = $series;
			$scope.show.popups.exercise_series = true;
		};
		
	}); //end controller

})();