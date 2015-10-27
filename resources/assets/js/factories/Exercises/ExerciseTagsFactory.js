angular.module('tracker')
    .factory('ExerciseTagsFactory', function ($http) {
        return {

            insert: function () {
                var $name = $("#create-exercise-tag").val();
                var $url = 'api/exerciseTags';
                var $data = {
                    name: $name
                };

                $("#create-exercise-tag").val("");

                return $http.post($url, $data);
            },

            destroy: function ($tag) {
                if (confirm("Are you sure you want to delete this tag?")) {
                    var $url = 'api/exerciseTags/' + $tag.id;

                    return $http.delete($url);
                }
            },
        }
    });