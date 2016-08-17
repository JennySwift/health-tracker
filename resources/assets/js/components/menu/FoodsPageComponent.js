module.exports = {
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
            store.showLoading();
            this.$http.get('/api/foods').then(function (response) {
                this.foods = response;
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
            store.showLoading();
            this.$http.get('/api/foods/' + food.id).then(function (response) {
                $.event.trigger('show-food-popup', [response]);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertFood: function () {
            store.showLoading();
            var data = {
                name: this.newFood.name
            };

            this.$http.post('/api/foods', data).then(function (response) {
                this.foods.push(response);
                $.event.trigger('provide-feedback', ['Food created', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteFood: function (food) {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/foods/' + food.id).then(function (response) {
                    this.foods = _.without(this.foods, food);
                    $.event.trigger('provide-feedback', ['Food deleted', 'success']);
                    store.hideLoading();
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('food-updated', function (event, food) {
                var index = _.indexOf(that.foods, _.findWhere(that.foods, {id: food.id}));
                that.foods[index].name = food.name;
                that.foods[index].defaultUnit = food.defaultUnit;
                that.foods[index].defaultCalories = food.defaultCalories;
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
        $(".wysiwyg").wysiwyg();
        this.getFoods();
        this.listen();
    }
};






