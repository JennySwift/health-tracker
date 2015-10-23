angular.module('tracker')
    .factory('ExerciseUnitsFactory', function ($http) {
        return {
            insert: function () {
                var $url = 'api/exerciseUnits';
                var $name = $("#create-new-exercise-unit").val();

                var $data = {
                    name: $name
                };

                $("#create-new-exercise-unit").val("");
                return $http.post($url, $data);
            },
            destroy: function ($unit) {
                if (confirm("Are you sure you want to delete this unit?")) {
                    var $url = 'api/exerciseUnits/' + $unit.id;

                    return $http.delete($url);
                }
            }
        }
    });