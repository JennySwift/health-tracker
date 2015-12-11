angular.module('tracker')
    .factory('ActivitiesFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/activities';
            
                return $http.get(url);
            },
            getTotalMinutesForDay: function (date) {
                return $http.get('/api/activities/getTotalMinutesForDay?date=' + date);
            }
        }
    });