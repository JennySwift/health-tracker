angular.module('tracker')
    .filter('formatDateTimeFilter', function () {
        return function (dateTime) {
            return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm:ssa DD/MM');
        }
    });

