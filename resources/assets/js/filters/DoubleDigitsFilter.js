angular.module('tracker')
    .filter('doubleDigitsFilter', function () {
        return function (number) {
            if (number < 10) {
                return '0' + number;
            }

            return number;
        }
    });

