angular.module('tracker')
    .factory('WorkoutsFactory', function ($http) {
        return {
            insertWorkout: function () {
                var $url = '/api/workouts';
                var $name = $("#workout").val();
                var $data = {
                    name: $name
                };

                $("#workout").val("");

                return $http.post($url, $data);
            }
        }
    });