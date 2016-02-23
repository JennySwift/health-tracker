var FoodPopup = Vue.component('food-popup', {
    template: '#food-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedFood: {
                //food: {},
                defaultUnit: {
                    data: {}
                },
                unitIds: []
            },
            units: []
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateFood: function (food) {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedFood.name,
                default_unit_id: this.selectedFood.defaultUnit.data.id,
                unit_ids: this.selectedFood.unitIds,
            };

            var that = this;

            setTimeout(function () {
                that.$http.put('/api/foods/' + that.selectedFood.id, data, function (response) {
                        that.selectedFood = response;
                        $.event.trigger('provide-feedback', ['Food updated', 'success']);
                        $.event.trigger('hide-loading');
                    })
                    .error(function (response) {
                        that.handleResponseError(response);
                    });
            }, 1000);


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
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foodUnits?includeCaloriesForSpecificFood=true&food_id=' + this.selectedFood.id, function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
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
                that.getUnits();
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
