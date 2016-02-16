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
        insertFoodIntoTemporaryRecipe: function () {
            //we are adding a food to a temporary recipe
            var $unit_name = $("#temporary-recipe-popup-unit option:selected").text();
            this.temporaryRecipePopup.contents.push({
                "food_id": this.temporaryRecipePopup.food.id,
                "name": this.temporaryRecipePopup.food.name,
                "quantity": this.temporaryRecipePopup.quantity,
                "unit_id": $("#temporary-recipe-popup-unit").val(),
                "unit_name": $unit_name,
                "units": this.temporaryRecipePopup.food.units
            });

            $("#temporary-recipe-food-input").val("").focus();
        },

        //this.$watch('recipe.portion', function (newValue, oldValue) {
//    $(this.temporaryRecipePopup.contents).each(function () {
//        if (this.original_quantity) {
//            //making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
//            this.quantity = this.original_quantity * newValue;
//        }
//    });
//});

        /**
         *
         */
        deleteIngredientFromTemporaryRecipe: function (ingredient) {
            this.recipe.ingredients.data = _.without(this.recipe.ingredients.data, ingredient);
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
