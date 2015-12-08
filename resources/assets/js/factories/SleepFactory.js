angular.module('tracker')
    .factory('SleepFactory', function ($http) {

        return {
            getEntries: function () {
                var $url = 'api/sleep?byDate=true';
                
                return $http.get($url);
            },
            store: function (entry) {
                var url = '/api/sleep';

                var start = Date.parse(entry.start);

                if (entry.startedYesterday) {
                    start = start.add({hours: -24});
                }

                start = start.toString('yyyy-MM-dd HH:mm:ss');

                var data = {
                    start: start,
                    finish: Date.parse(entry.finish).toString('yyyy-MM-dd HH:mm:ss')
                };

                return $http.post(url, data);
            }
        }
    });