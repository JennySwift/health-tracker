angular.module('tracker')
    .factory('ActivitiesFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/activities';
            
                return $http.get(url);
            },
            getTotalMinutesForDay: function () {
                return $http.get('/api/activities/getTotalMinutesForDay');
            }
        }
    });