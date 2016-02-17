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

        /**
        *
        */
        getFood: function (food) {
            $.event.trigger('show-loading');
            this.$http.get('/api/foods/' + food.id, function (response) {
                $.event.trigger('show-food-popup', [response]);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
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







