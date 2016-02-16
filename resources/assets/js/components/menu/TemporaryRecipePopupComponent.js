var TemporaryRecipePopup = Vue.component('temporary-recipe-popup', {
    template: '#temporary-recipe-popup-template',
    data: function () {
        return {
            showPopup: false,
            recipe: {
                ingredients: {}
            }
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getRecipe: function (recipe) {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes/' + recipe.id, function (response) {
                this.recipe = response;

                //$(this.recipe.contents).each(function () {
                //    this.originalQuantity = this.quantity;
                //});

                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
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
            $(document).on('show-temporary-recipe-popup', function (event, recipe) {
                that.getRecipe(recipe);
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
