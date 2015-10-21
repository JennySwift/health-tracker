angular.module('tracker')
    .factory('ExerciseUnitsFactory', function ($http) {
        return {
            insertExerciseUnit: function () {
                var $url = 'insert/exerciseUnit';
                var $name = $("#create-new-exercise-unit").val();

                var $data = {
                    name: $name
                };

                $("#create-new-exercise-unit").val("");
                return $http.post($url, $data);
            },
            deleteExerciseUnit: function ($id) {
                if (confirm("Are you sure you want to delete this unit?")) {
                    var $url = 'delete/exerciseUnit';
                    var $data = {
                        id: $id
                    };

                    return $http.post($url, $data);
                }
            },
        }
    });