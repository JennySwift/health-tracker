var app = angular.module('tracker');

(function () {
    app.controller('entries', function ($rootScope, $scope, $http, DatesFactory, AutocompleteFactory, WeightsFactory) {

        $scope.weight = weight;

        $scope.calories = {
            day: calories_for_the_day,
            week_avg: calories_for_the_week
        };

        $scope.show = {
            autocomplete_options: {},
            popups: {}
        };

        //new entry
        $scope.new_entry = {
            exercise: {
                unit: {}
            },
            exercise_unit: {},
            menu: {},
            food: {},
        };

        //autocomplete
        $scope.autocomplete_options = {
            exercises: {},
            menu_items: {},
            foods: {},
            temporary_recipe_foods: {}
        };


        $scope.edit_weight = false;

        $scope.date = {};

        if ($scope.date.typed === undefined) {
            $scope.date.typed = Date.parse('today').toString('dd/MM/yyyy');
        }
        $scope.date.long = Date.parse($scope.date.typed).toString('dd MMM yyyy');

        $scope.goToDate = function ($number) {
            $scope.date.typed = DatesFactory.goToDate($scope.date.typed, $number);
        };

        $scope.today = function () {
            $scope.date.typed = DatesFactory.today();
        };
        $scope.changeDate = function ($keycode) {
            if ($keycode === 13) {
                $scope.date.typed = DatesFactory.changeDate($keycode, $("#date").val());
            }
        };

        /**
         * watches
         */

        $scope.$watch('date.typed', function (newValue, oldValue) {
            $scope.date.sql = Date.parse($scope.date.typed).toString('yyyy-MM-dd');
            $scope.date.long = Date.parse($scope.date.typed).toString('ddd dd MMM yyyy');
            $("#date").val(newValue);

            if (newValue === oldValue) {
                // $scope.pageLoad();
            }
            else {
                $scope.getEntries();
            }
        });

        $("#food").val("");
        $("#weight").val("");

        /**
         * Get all the user's entries for the current date
         */
        $scope.getEntries = function () {
            $rootScope.$emit('getEntries');
            //Get weight
            //Get exercise entries
            //Get menu entries
            //Get calories for the day
            //Get calorie average for the week
        };

        $rootScope.$on('getEntries', function () {
            $rootScope.showLoading();
            WeightsFactory.getEntriesForTheDay($scope.date.sql)
                .then(function (response) {
                    $scope.weight = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        });

        $scope.insertOrUpdateWeight = function ($keycode) {
            if ($keycode === 13) {
                WeightsFactory.insertWeight($scope.date.sql).then(function (response) {
                    $scope.weight = response.data;
                    $scope.edit_weight = false;
                    $("#weight").val("");
                });
            }
        };

        $scope.editWeight = function () {
            $scope.edit_weight = true;
            setTimeout(function () {
                $("#weight").focus();
            }, 500);
        };

        /**
         * media queries
         */

        enquire.register("screen and (max-width: 890px", {
            match: function () {
                $("#avg-calories-for-the-week-text").text('Avg: ');
            },
            unmatch: function () {
                $("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
            }
        });

    });

})();