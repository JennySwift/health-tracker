angular.module('tracker')
    .factory('ActivitiesFactory', function ($http) {
        return {
            getTotalMinutesForDay: function (date) {
                return $http.get('/api/activities/getTotalMinutesForDay?date=' + date);
            },
            getTotalMinutesForWeek: function (date) {
                return $http.get('/api/activities/getTotalMinutesForWeek?date=' + date);
            },
        }
    });