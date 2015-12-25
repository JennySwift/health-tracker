app.factory('ExercisesFactory', function ($http) {
    return {

        show: function ($exercise) {
            var $url = 'api/exercises/' + $exercise.id;

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

        insert: function () {
            var $url = 'api/exercises';
            var $name = $("#create-new-exercise").val();
            var $description = $("#exercise-description").val();

            var $data = {
                name: $name,
                description: $description
            };

            $("#create-new-exercise, #exercise-description").val("");
            return $http.post($url, $data);
        },

        update: function ($exercise) {
            var $url = 'api/exercises/' + $exercise.id;

            var $data = {
                name: $exercise.name,
                step_number: $exercise.stepNumber,
                series_id: $exercise.series.id,
                default_quantity: $exercise.defaultQuantity,
                description: $exercise.description,
                default_unit_id: $exercise.defaultUnit.id,
                program_id: $exercise.program.id,
                target: $exercise.target,
                priority: $exercise.priority
            };

            $("#exercise-step-number").val("");

            return $http.put($url, $data);
        },

        destroy: function ($exercise) {
            if (confirm("Are you sure you want to delete this exercise?")) {
                var $url = 'api/exercises/' + $exercise.id;

                return $http.delete($url);
            }
        },
    };
});