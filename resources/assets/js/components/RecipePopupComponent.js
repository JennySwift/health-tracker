var RecipePopup = Vue.component('recipe-popup', {
    template: '#recipe-popup-template',
    data: function () {
        return {
            showRecipePopup: false,
            selectedRecipe: {},
            newIngredient: {
                food: {}
            },
            array: [1,2]
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-recipe-popup', function (event, recipe) {
                that.showPopup(recipe);
            });
        },

        /**
         *
         */
        showPopup: function (recipe) {
            this.selectedRecipe = recipe;
            this.showRecipePopup = true;
        },

        /**
         *
         */
        closePopup: function () {
            this.showRecipePopup = false;
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
        'tags'
    ],
    ready: function () {
        this.listen();
    }
});
