app.factory('ExercisesFactory', function ($http) {
	return {

		getExerciseInfo: function ($exercise) {
            var $url = $exercise.path;

            return $http.get($url);
		},

		insertTagsInExercise: function ($exercise_id, $tags) {
			var $url = 'insert/tagsInExercise';
			var $data = {
				exercise_id: $exercise_id,
				tags: $tags
			};

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

        updateExercise: function ($exercise) {
            var $url = $exercise.path;

            var $data = {
                exercise: $exercise
            };

            $("#exercise-step-number").val("");

            return $http.put($url, $data);
        },

		deleteExercise: function ($exercise) {
			if (confirm("Are you sure you want to delete this exercise?")) {
                var $url = $exercise.path;
				
				return $http.delete($url);
			}
		},
	};
});