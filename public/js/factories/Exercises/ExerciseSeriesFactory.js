angular.module('tracker')
    .factory('ExerciseSeriesFactory', function ($http) {
        return {

            getExerciseSeriesInfo: function ($series) {
                var $url = $series.path;

                return $http.get($url);
            },
            getExerciseSeriesHistory: function ($series_id) {
                var $url = 'select/exerciseSeriesHistory';
                var $data = {
                    series_id: $series_id
                };

                return $http.post($url, $data);
            },
            insertExerciseSeries: function () {
                var $name = $("#exercise-series").val();
                var $url = '/exerciseSeries';
                var $data = {
                    name: $name
                };

                $("#exercise-series").val("");

                return $http.post($url, $data);
            },
            deleteExerciseSeries: function ($series) {
                if (confirm("Are you sure you want to delete this series?")) {
                    var $url = $series.path;

                    return $http.delete($url);
                }
            }
        }
    });