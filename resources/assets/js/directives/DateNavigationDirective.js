angular.module('tracker')
    .directive('dateNavigationDirective', function ($rootScope, DatesFactory) {
        return {
            scope: {
                "date": "=date"
            },
            templateUrl: 'date-navigation-template',

            link: function ($scope) {
                $scope.date = DatesFactory.setDate($scope.date);

                $scope.goToDate = function ($number) {
                    $scope.date.typed = DatesFactory.goToDate($scope.date.typed, $number);
                };

                $scope.today = function () {
                    $scope.date.typed = DatesFactory.today();
                };
                $scope.changeDate = function ($keycode, $date) {
                    if ($keycode !== 13) {
                        return false;
                    }
                    var $date = $date || $("#date").val();
                    $scope.date.typed = DatesFactory.changeDate($keycode, $date);
                };

                $scope.$watch('date.typed', function (newValue, oldValue) {
                    $scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
                    $scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
                    $("#date").val(newValue);

                    if (newValue === oldValue) {
                        // $scope.pageLoad();
                    }
                    else {
                        $rootScope.$broadcast('changeDate');
                    }
                });

            }
        }
    });