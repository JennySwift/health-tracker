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

        insert: function (exercise) {
            var $url = 'api/exercises';

            var $data = {
                name: exercise.name,
                description: exercise.description,
                priority: exercise.priority,
                step_number: exercise.stepNumber,
                default_quantity: exercise.defaultQuantity,
                target: exercise.target,
                program_id: exercise.program.id,
                series_id: exercise.series.id,
                default_unit_id: exercise.defaultUnit.id,
            };

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