angular.module('tracker')
    .factory('ActivitiesFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/activities';
            
                return $http.get(url);
            },
            getTotalMinutesForDay: function (date) {
                return $http.get('/api/activities/getTotalMinutesForDay?date=' + date);
            },
            getTotalMinutesForWeek: function (date) {
                return $http.get('/api/activities/getTotalMinutesForWeek?date=' + date);
            },
            store: function (activity) {
                var url = '/api/activities';
                var data = {
                    name: activity.name,
                    color: activity.color
                };
                
                return $http.post(url, data);
            },
            update: function (activity) {
                var url = '/api/activities/' + activity.id;
                var data = {
                    name: activity.name,
                    color: activity.color
                };

                return $http.put(url, data);
            },
            destroy: function (activity) {
                var url = '/api/activities/' + activity.id;

                return $http.delete(url);
            }
        }
    });