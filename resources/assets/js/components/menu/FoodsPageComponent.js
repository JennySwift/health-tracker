var FoodsPage = Vue.component('foods-page', {
    template: '#foods-page-template',
    data: function () {
        return {
            calories: {},
            newItem: {},
            foods: [],
            foodsFilter: '',
            newFood: {}
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getFoods: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foods', function (response) {
                this.foods = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        //getMenu: function () {
        //    if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
        //        $scope.menu = select.getMenu($scope.foods, $scope.recipes);
        //    }
        //},

        getFoodInfo: function ($food) {
            //for popup where user selects units for food and enters calories
            $scope.food_popup.id = $food.id;
            $scope.food_popup.name = $food.name;
            $scope.show.popups.food_info = true;
            FoodsFactory.getFoodInfo($food).then(function (response) {
                $scope.food_popup = response.data;
            });

        },

        /**
         * Add a unit to a food or remove the unit from the food.
         * The method name is old and should probably be changed.
         * @param $unit_id
         */
        insertOrDeleteUnitInCalories: function ($unit_id) {
            //Check if the checkbox is checked
            if ($scope.food_popup.food_units.indexOf($unit_id) === -1) {
                //It is now unchecked. Remove the unit from the food.
                FoodsFactory.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
            else {
                // It is now checked. Add the unit to the food.
                FoodsFactory.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
        },

        /**
        *
        */
        insertFood: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newFood.name
            };

            this.$http.post('/api/foods', data, function (response) {
                this.foods.push(response);
                $.event.trigger('provide-feedback', ['Food created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        updateCalories: function ($keycode, $unit_id, $calories) {
            if ($keycode === 13) {
                FoodsFactory.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
        },

        updateDefaultUnit: function ($food_id, $unit_id) {
            FoodsFactory.updateDefaultUnit($food_id, $unit_id).then(function (response) {
                $scope.food_popup = response.data;
            });
        },

        /**
        *
        */
        deleteFood: function (food) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/foods/' + food.id, function (response) {
                    this.foods = _.without(this.foods, food);
                    $.event.trigger('provide-feedback', ['Food deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        $(".wysiwyg").wysiwyg();
        this.getFoods();
    }
});







