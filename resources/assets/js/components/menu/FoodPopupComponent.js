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
        updateFood: function () {
            store.showLoading();

            var data = {
                name: this.selectedFood.name,
                default_unit_id: this.selectedFood.defaultUnit.data.id,
                unit_ids: this.selectedFood.unitIds,
            };

            var that = this;

            setTimeout(function () {
                that.$http.put('/api/foods/' + that.selectedFood.id, data).then(function (response) {
                    that.selectedFood = response;
                    $.event.trigger('provide-feedback', ['Food updated', 'success']);
                    $.event.trigger('food-updated', [response]);
                    store.hideLoading();
                }, function (response) {
                    that.handleResponseError(response);
                });
            }, 1000);
        },

        /**
         *
         */
        updateDefaultUnit: function (unit) {
            store.showLoading();

            this.selectedFood.defaultUnit.data = unit;

            var data = {
                default_unit_id: this.selectedFood.defaultUnit.data.id,
            };

            this.$http.put('/api/foods/' + this.selectedFood.id, data).then(function (response) {
                this.selectedFood = response;
                $.event.trigger('provide-feedback', ['Food updated', 'success']);
                $.event.trigger('food-updated', [response]);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        updateCalories: function (unit) {
            store.showLoading();

            var data = {
                updatingCalories: true,
                unit_id: unit.id,
                calories: unit.calories
            };

            this.$http.put('/api/foods/' + this.selectedFood.id, data).then(function (response) {
                $.event.trigger('provide-feedback', ['Calories updated', 'success']);
                $.event.trigger('food-updated', [response]);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getUnits: function () {
            store.showLoading();
            this.$http.get('/api/foodUnits?includeCaloriesForSpecificFood=true&food_id=' + this.selectedFood.id).then(function (response) {
                this.units = response;
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
            $.event.trigger('response-error', [response]);
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
