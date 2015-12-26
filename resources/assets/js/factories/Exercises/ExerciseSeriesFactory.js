angular.module('tracker')
    .factory('ExerciseSeriesFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/exerciseSeries';

                return $http.get(url);
            },

            getExerciseSeriesInfo: function ($series) {
                var $url = 'api/exerciseSeries/' + $series.id;

                return $http.get($url);
            },
            getExerciseSeriesHistory: function ($series) {
                var $url = 'api/seriesEntries/' + $series.id;

                return $http.get($url);
            },
            insert: function (series) {
                var $url = 'api/exerciseSeries';
                var $data = {
                    name: series.name
                };

                series.name = '';

                return $http.post($url, $data);
            },
            update: function ($series) {
                var $url = 'api/exerciseSeries/' + $series.id;
                var $data = {
                    name: $series.name,
                    priority: $series.priority,
                    workout_ids: $series.workout_ids
                };

                return $http.put($url, $data);
            },
            destroy: function ($series) {
                if (confirm("Are you sure you want to delete this series?")) {
                    var $url = 'api/exerciseSeries/' + $series.id;

                    return $http.delete($url);
                }
            }
        }
    });