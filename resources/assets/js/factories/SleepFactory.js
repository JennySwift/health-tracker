angular.module('tracker')
    .factory('SleepFactory', function ($http) {

        return {
            getEntries: function () {
                var $url = 'api/sleep';
                
                return $http.get($url);
            }
        }
    });