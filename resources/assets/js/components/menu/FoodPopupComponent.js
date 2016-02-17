var FoodPopup = Vue.component('food-popup', {
    template: '#food-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedFood: {
                food: {}
            }
        };
    },
    components: {},
    methods: {

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
         * @param $event
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-food-popup', function (event, food) {
                that.selectedFood = food;
                that.showPopup = true;
            });
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
        this.listen();
    }
});
