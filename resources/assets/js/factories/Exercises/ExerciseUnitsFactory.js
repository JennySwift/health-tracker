angular.module('tracker')
    .factory('ExerciseUnitsFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/exerciseUnits';

                return $http.get(url);
            }
        }
    });