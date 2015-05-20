app.factory('exercises', function ($http) {
	return {
		/**
		 * select
		 */
		
		getExercises: function () {
			var $url = 'select/getExercises';
			return $http.post($url);
		},
		getExerciseInfo: function ($exercise_id) {
			var $url = 'select/getExerciseInfo';
			var $data = {
				exercise_id: $exercise_id
			};
			
			return $http.post($url, $data);
		},
		getSpecificExerciseEntries: function ($sql_date, $exercise_id, $exercise_unit_id) {
			var $url = 'select/specificExerciseEntries';
			var $data = {
				date: $sql_date,
				exercise_id: $exercise_id,
				exercise_unit_id: $exercise_unit_id
			};
			
			return $http.post($url, $data);
		},
		getExerciseSeriesHistory: function ($series_id) {
			var $url = 'select/exerciseSeriesHistory';
			var $data = {
				series_id: $series_id
			};
			
			return $http.post($url, $data);
		},

		/**
		 * insert
		 */
		
		insertTagsInExercise: function ($exercise_id, $tags) {
			var $url = 'insert/tagsInExercise';
			var $data = {
				exercise_id: $exercise_id,
				tags: $tags
			};
			
			return $http.post($url, $data);
		},
		insertWorkout: function () {
			var $url = 'insert/workout';
			var $name = $("#workout").val();
			var $data = {
				name: $name
			};
			
			$("#workout").val("");

			return $http.post($url, $data);
		},
		insertExerciseEntry: function ($sql_date, $new_entry) {
			var $url = 'insert/exerciseEntry';
		
			var $data = {
				date: $sql_date,
				new_entry: $new_entry,
			};

			$("#exercise").val("").focus();

			return $http.post($url, $data);
		},
		insertExerciseSet: function ($sql_date, $exercise_id) {
			var $url = 'insert/exerciseSet';
			var $data = {
				date: $sql_date,
				exercise_id: $exercise_id
			};
			
			return $http.post($url, $data);
		},
		insertExercise: function () {
			var $url = 'insert/exercise';
			var $name = $("#create-new-exercise").val();
			var $description = $("#exercise-description").val();
			
			var $data = {
				name: $name,
				description: $description
			};

			$("#create-new-exercise, #exercise-description").val("");		
			return $http.post($url, $data);
		},
		insertSeriesIntoWorkout: function ($workout_id, $series_id) {
			var $url = 'insert/seriesIntoWorkout';
			var $data = {
				workout_id: $workout_id,
				series_id: $series_id
			};
			
			return $http.post($url, $data);
		},
		insertExerciseSeries: function () {
			var $name = $("#exercise-series").val();
			var $url = 'insert/exerciseSeries';
			var $data = {
				name: $name
			};

			$("#exercise-series").val("");
			
			return $http.post($url, $data);
		},

		/**
		 * update
		 */
		
		updateDefaultExerciseUnit: function ($exercise_id, $default_exercise_unit_id) {
			var $url = 'update/defaultExerciseUnit';
			var $data = {
				exercise_id: $exercise_id,
				default_exercise_unit_id: $default_exercise_unit_id
			};
			
			return $http.post($url, $data);
		},
		updateExerciseSeries: function ($exercise_id, $series_id) {
			var $url = 'update/exerciseSeries';
			var $data = {
				exercise_id: $exercise_id,
				series_id: $series_id
			};
			
			return $http.post($url, $data);
		},
		updateExerciseStepNumber: function ($exercise_id) {
			var $url = 'update/exerciseStepNumber';
			var $step_number = $("#exercise-step-number").val();
			var $data = {
				exercise_id: $exercise_id,
				step_number: $step_number
			};

			$("#exercise-step-number").val("");
			
			return $http.post($url, $data);
		},
		updateDefaultExerciseQuantity: function ($id) {
			var $quantity = $("#default-unit-quantity").val();
			var $url = 'update/defaultExerciseQuantity';
			var $data = {
				id: $id,
				quantity: $quantity
			};
			
			return $http.post($url, $data);
		},

		/**
		 * delete
		 */

		deleteAndInsertSeriesIntoWorkouts: function ($series_id, $workouts) {
			var $url = 'insert/deleteAndInsertSeriesIntoWorkouts';
			var $data = {
				series_id: $series_id,
				workouts: $workouts
			};
			
			return $http.post($url, $data);
		},
		
		deleteExerciseSeries: function ($id) {
			if (confirm("Are you sure you want to delete this series?")) {
				var $url = 'delete/exerciseSeries';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteExercise: function ($id) {
			if (confirm("Are you sure you want to delete this exercise?")) {
				var $url = 'delete/exercise';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteExerciseEntry: function ($id, $sql_date) {
			if (confirm("Are you sure you want to delete this entry?")) {
				var $url = 'delete/exerciseEntry';
				var $data = {
					id: $id,
					date: $sql_date
				};
				
				return $http.post($url, $data);
			}
		},
	};
});