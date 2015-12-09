angular.module('tracker')
    .filter('MinutesToTimeFilter', function () {
        return function (totalMinutes) {
            return totalMinutes / 60;
        }
    });

