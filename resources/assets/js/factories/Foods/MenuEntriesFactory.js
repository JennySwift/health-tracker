angular.module('tracker')
    .factory('MenuEntriesFactory', function ($http) {
        return {
            getEntriesForTheDay: function ($date) {
                var $url = 'api/menuEntries/' + $date;
                return $http.get($url);
            }
        }
    });