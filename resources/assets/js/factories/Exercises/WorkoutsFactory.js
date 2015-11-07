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
            },
            insertSeriesIntoWorkout: function ($workout_id, $series_id) {
                var $url = 'insert/seriesIntoWorkout';
                var $data = {
                    workout_id: $workout_id,
                    series_id: $series_id
                };

                return $http.post($url, $data);
            },
            deleteAndInsertSeriesIntoWorkouts: function ($series_id, $workouts) {
                var $url = 'insert/deleteAndInsertSeriesIntoWorkouts';
                var $data = {
                    series_id: $series_id,
                    workout_ids: $workouts
                };

                return $http.post($url, $data);
            },
        }
    });