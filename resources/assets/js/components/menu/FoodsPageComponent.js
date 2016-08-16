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
            $.event.trigger('show-loading');
            this.$http.get('/api/foods').then(function (response) {
                this.foods = response;
                $.event.trigger('hide-loading');
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
            $.event.trigger('show-loading');
            this.$http.get('/api/foods/' + food.id).then(function (response) {
                $.event.trigger('show-food-popup', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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

            this.$http.post('/api/foods', data).then(function (response) {
                this.foods.push(response);
                $.event.trigger('provide-feedback', ['Food created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteFood: function (food) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/foods/' + food.id).then(function (response) {
                    this.foods = _.without(this.foods, food);
                    $.event.trigger('provide-feedback', ['Food deleted', 'success']);
                    $.event.trigger('hide-loading');
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






