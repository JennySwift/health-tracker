angular.module('tracker')
    .factory('ExerciseTagsFactory', function ($http) {
        return {

            insertExerciseTag: function () {
                var $name = $("#create-exercise-tag").val();
                var $url = 'insert/exerciseTag';
                var $data = {
                    name: $name
                };

                $("#create-exercise-tag").val("");

                return $http.post($url, $data);
            },

            deleteExerciseTag: function ($id) {
                if (confirm("Are you sure you want to delete this tag?")) {
                    var $url = 'delete/exerciseTag';
                    var $data = {
                        id: $id
                    };

                    return $http.post($url, $data);
                }
            },
        }
    });