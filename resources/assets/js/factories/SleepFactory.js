angular.module('tracker')
    .factory('SleepFactory', function ($http) {

        return {
            getEntries: function () {
                var $url = 'api/sleep?byDate=true';
                
                return $http.get($url);
            }
        }
    });