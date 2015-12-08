angular.module('tracker')
    .factory('SleepFactory', function ($http) {

        return {
            getEntries: function () {
                var $url = 'api/sleep?byDate=true';
                
                return $http.get($url);
            },
            store: function (entry, date) {
                var url = '/api/sleep';

                var startDate = date;
                var finishDate = date;

                if (entry.startedYesterday) {
                    startDate = moment(startDate, 'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
                }

                var startTime = Date.parse(entry.start).toString('HH:mm:ss');
                var finishTime = Date.parse(entry.finish).toString('HH:mm:ss');

                var data = {
                    start: startDate + ' ' + startTime,
                    finish: finishDate + ' ' + finishTime
                };

                return $http.post(url, data);
            }
        }
    });