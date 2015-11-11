var app = angular.module('tracker');

(function () {
    app.controller('entries', function ($rootScope, $scope, $http, DatesFactory, AutocompleteFactory, WeightsFactory, CaloriesFactory) {

        $scope.weight = weight;

        $scope.calories = {
            day: calories_for_the_day,
            week_avg: calories_for_the_week
        };

        $scope.showAutocompleteOptions = {};

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
         * Get all the user's entries for the current date:
         * exercise entries
         * menu entries
         * weight
         * calories for the day
         * calorie average for the week
         */
        $scope.getEntries = function () {
            $rootScope.$emit('getEntries');
        };

        $rootScope.$on('getEntries', function () {
            $rootScope.showLoading();
            //Get weight for the day
            WeightsFactory.getWeightForTheDay($scope.date.sql)
                .then(function (response) {
                    $scope.weight = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
            //Get calories for the day and average calories for 7 days
            $rootScope.showLoading();
            CaloriesFactory.getInfoForTheDay($scope.date.sql)
                .then(function (response) {
                    $scope.calories.day = response.data.forTheDay;
                    $scope.calories.week_avg = response.data.averageFor7Days;
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