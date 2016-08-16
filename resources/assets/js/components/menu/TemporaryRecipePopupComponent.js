module.exports = {
    template: '#temporary-recipe-popup-template',
    data: function () {
        return {
            showPopup: false,
            portion: 1,
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
            this.$http.get('/api/recipes/' + recipe.id).then(function (response) {
                this.recipe = response;

                $(this.recipe.ingredients.data).each(function () {
                    this.originalQuantity = this.quantity;
                });

                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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

        /**
         *
         */
        setRecipePortion: function () {
            var that = this;
            $(this.recipe.ingredients.data).each(function () {
                if (this.originalQuantity) {
                    //making sure we don't alter the quantity of a food
                    //that has been added to the temporary recipe
                    //(by doing the if check)
                    this.quantity = this.originalQuantity * that.portion;
                }
            });
        },

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
        insertEntriesForRecipe: function () {
            $.event.trigger('insert-entries-for-recipe', [this.recipe]);
            this.showPopup = false;
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
            $(document).on('add-ingredient-to-temporary-recipe', function (event, ingredient) {
                console.log(ingredient);
                console.log(that.recipe.ingredients.data[0]);
                that.recipe.ingredients.data.push({
                    food: {
                        data: {
                            id: ingredient.food.id,
                            name: ingredient.food.name,
                            units: {data: ingredient.food.units.data},
                            defaultUnit: ingredient.food.defaultUnit,
                        }
                    },
                    unit: {data: ingredient.unit},
                    quantity: ingredient.quantity
                });
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
        //'insertEntriesForRecipe'
    ],
    ready: function () {
        this.listen();
    }
};
