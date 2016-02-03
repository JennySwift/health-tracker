app.factory('ExercisesFactory', function ($http) {
    return {

        insertTagsInExercise: function ($exercise_id, $tags) {
            var $url = 'insert/tagsInExercise';
            var $data = {
                exercise_id: $exercise_id,
                tags: $tags
            };

            return $http.post($url, $data);
        },

        destroy: function ($exercise) {
            if (confirm("Are you sure you want to delete this exercise?")) {
                var $url = 'api/exercises/' + $exercise.id;

                return $http.delete($url);
            }
        },
    };
});