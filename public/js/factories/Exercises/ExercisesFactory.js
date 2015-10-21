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
			var $url = '/exerciseSeries';
			var $data = {
				name: $name
			};

			$("#exercise-series").val("");
			
			return $http.post($url, $data);
		},

		/**
		 * update
		 */

        updateExercise: function ($exercise) {
            var $url = $exercise.path;

            var $data = {
                exercise: $exercise
            };

            $("#exercise-step-number").val("");

            return $http.put($url, $data);
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