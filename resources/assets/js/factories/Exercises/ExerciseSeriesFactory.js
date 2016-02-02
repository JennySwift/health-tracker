angular.module('tracker')
    .factory('ExerciseSeriesFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/exerciseSeries';

                return $http.get(url);
            },
        }
    });