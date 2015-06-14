app.factory('ExercisesFactory', function ($http) {
	return {
		/**
		 * select
		 */

		getExerciseInfo: function ($exercise) {
            var $url = $exercise.path;

            return $http.get($url);
		},
		getExerciseSeriesInfo: function ($series) {
            var $url = $series.path;

            return $http.get($url);
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
			var $url = '/workouts';
			var $name = $("#workout").val();
			var $data = {
				name: $name
			};

			$("#workout").val("");

			return $http.post($url, $data);
		},
		insertExercise: function () {
			var $url = '/exercises';
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
			var $url = '/ExerciseSeries';
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
		//updateExerciseStepNumber: function ($exercise) {
         //   var $url = $exercise.path;
         //   var $step_number = $("#exercise-step-number").val();
        //
         //   var $data = {
         //       step_number: $step_number
         //   };
        //
         //   $("#exercise-step-number").val("");
        //
         //   return $http.put($url, $data);
		//},
        updateExerciseStepNumber: function ($exercise) {
            var $url = $exercise.path;
            var $step_number = $("#exercise-step-number").val();

            var $data = {
                exercise: $exercise
            };

            $("#exercise-step-number").val("");

            return $http.put($url, $data);
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
				workout_ids: $workouts
			};
			
			return $http.post($url, $data);
		},
		
		deleteExerciseSeries: function ($series) {
			if (confirm("Are you sure you want to delete this series?")) {
				var $url = $series.path;

				return $http.delete($url);
			}
		},
		deleteExercise: function ($exercise) {
			if (confirm("Are you sure you want to delete this exercise?")) {
                var $url = $exercise.path;
				
				return $http.delete($url);
			}
		},
	};
});