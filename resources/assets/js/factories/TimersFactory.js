angular.module('tracker')
    .factory('TimersFactory', function ($http) {

        return {
            getEntries: function () {
                var $url = '/api/timers?byDate=true';
                
                return $http.get($url);
            },

            store: function (entry, date) {
                var data = {
                    start: this.calculateStartDateTime(entry, date)
                };

                if (entry.finish) {
                    data.finish = this.calculateFinishTime(entry, date);
                }

                if (entry.activity) {
                    data.activity_id = entry.activity.id;
                }

                return $http.post('/api/timers', data);
            },

            calculateStartDateTime: function (entry, date) {
                if (date) {
                    return this.calculateStartDate(entry, date) + ' ' + this.calculateStartTime(entry);
                }
                else {
                    //The 'start' timer button has been clicked rather than entering the time manually, so make the start now
                    return moment().format('YYYY-MM-DD HH:mm:ss');
                }

            },

            calculateStartDate: function (entry, date) {
                if (entry.startedYesterday) {
                    return moment(date, 'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
                }
                else {
                    return date;
                }
            },

            calculateFinishTime: function (entry, date) {
                if (entry.finish) {
                    return date + ' ' + Date.parse(entry.finish).toString('HH:mm:ss');
                }
                else {
                    //The stop timer button has been pressed. Make the finish time now.
                    return moment().format('YYYY-MM-DD HH:mm:ss');
                }

            },

            calculateStartTime: function (entry) {
                return Date.parse(entry.start).toString('HH:mm:ss');
            },
            
            update: function (timer) {
                var url = '/api/timers/' + timer.id;
                var data = {
                    finish: this.calculateFinishTime(timer)
                };
                
                return $http.put(url, data);
            }
        }
    });