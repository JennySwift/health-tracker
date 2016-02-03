var FoodUnitsPage = Vue.component('food-units-page', {
    template: '#food-units-page-template',
    data: function () {
        return {
            units: []
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foodUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        insertFoodUnit: function ($keycode) {
            if ($keycode === 13) {
                //$scope.showLoading();
                FoodUnitsFactory.insert()
                    .then(function (response) {
                        $scope.units.push(response.data.data);
                        $rootScope.$broadcast('provideFeedback', 'Unit created');
                        //$scope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    });
            }
        },

        deleteFoodUnit: function ($unit) {
            //$scope.showLoading();
            FoodUnitsFactory.destroy($unit)
                .then(function (response) {
                    $scope.units = _.without($scope.units, $unit);
                    $rootScope.$broadcast('provideFeedback', 'Unit deleted');
                    //$scope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
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
        this.getUnits();
    }
});

