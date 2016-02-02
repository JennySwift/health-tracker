//angular.module('tracker')
//    .filter('formatDateTimeFilter', function () {
//        return function (dateTime, format) {
//            if (!format) {
//                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm:ssa DD/MM');
//            }
//            else if (format === 'seconds') {
//                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss a DD/MM');
//            }
//            else if (format === 'hoursAndMinutes') {
//                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm');
//            }
//            else if (format === 'object') {
//                return {
//                    seconds: moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss')
//                };
//            }
//        }
//    });

