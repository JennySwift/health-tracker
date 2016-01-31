var NewQuickRecipe = Vue.component('new-quick-recipe', {
    template: '#new-quick-recipe-template',
    data: function () {
        return {
            errors: {},
            showPopup: false,
            showHelp: false,
            newRecipe: {
                similarNames: []
            },
            similarNames: [],
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        toggleHelp: function () {
            this.showHelp = !this.showHelp;
        },

        /**
         * End goal of the function:
         * Call RecipesFactory.insertQuickRecipe, with $check_similar_names as true.
         * Send the contents, steps, and name of new recipe.
         *
         * The PHP checks for similar names and returns similar names if found.
         * The JS checks for similar names in the response.
         *
         * If they exist, a popup shows.
         * From there, the user can click a button
         * which fires quickRecipeFinish,
         * sending the recipe info again
         * but this time without the similar name check.
         *
         * If none exist, the recipe should have been entered with the PHP
         * and things should update accordingly on the page.
         */
        respondToEnterRecipeBtnClick: function () {
            //remove any previous error styling so it doesn't wreck up the html
            $("#quick-recipe > *").removeAttr("style");
            $("#quick-recipe-errors").hide();

            this.addPropertiesToRecipe(RecipesRepository.getArrayOfIngredientsAndSteps());

            if (this.errors.length < 1) {
                //Prompt the user for the recipe name
                this.newRecipe.name = prompt('name your recipe');

                //If the user changes their mind and cancels
                if (!this.newRecipe.name) {
                    return;
                }

                this.checkForSimilarNames();
            }
        },

        /**
        *
        */
        showNewRecipeFields: function () {
            this.addingNewRecipe = true;
            this.editingRecipe = false;
        },

        /**
         *
         */
        checkForSimilarNames: function () {
            $.event.trigger('show-loading');

            var data = {
                ingredients: this.newRecipe.ingredients
            };

            this.$http.get('/api/quickRecipes/checkForSimilarNames', data, function (response) {
                $.event.trigger('hide-loading');
                this.similarNames = response;

                if (response.units || response.foods) {
                    $.event.trigger('provide-feedback', ['Similar names were found', 'success']);
                    this.showPopup = true;
                }
                else {
                    this.insertRecipe();
                }

            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertRecipe: function () {
            $.event.trigger('show-loading');
            if (this.similarNames.foods || this.similarNames.units) {
                this.chooseCorrectFoodName();
                this.chooseCorrectUnitName();
                this.showPopup = false;
            }

            var data = {
                name: this.newRecipe.name,
                ingredients: this.newRecipe.ingredients,
                steps: this.newRecipe.steps,
                checkForSimilarNames: this.checkForSimilarNames
            };

            this.$http.post('/api/quickRecipes', data, function (response) {
                    $.event.trigger('provide-feedback', ['Recipe created', 'success']);
                    $.event.trigger('hide-loading');
                    this.recipes.push(response.data);
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        chooseCorrectFoodName: function () {
            $(this.similarNames.foods).each(function () {
                if (this.checked === this.existingFood.name) {
                    // Use the existing food rather than creating a new food.
                    this.newRecipe.ingredients[this.index].food = this.existingFood.name;
                }
            });
        },

        chooseCorrectUnitName: function () {
            $(this.newRecipe.similarNames.units).each(function () {
                if (this.checked === this.existingUnit.name) {
                    //Use the existing unit rather than creating a new unit
                     this.newRecipe.ingredients[this.index].unit = this.existingUnit.name;
                }
            });
        },

        /**
         * Check item contains quantity, unit and food
         * and convert quantities to decimals if necessary
         */
        checkForAndHandleErrors: function () {
            var itemsAndErrors = RecipesRepository.errorCheck(this.newRecipe.ingredients);
            this.newRecipe.ingredients = itemsAndErrors.ingredients;

            if (itemsAndErrors.errors.length > 0) {
                this.errors = itemsAndErrors.errors;
                $("#quick-recipe-errors").show();
            }
        },

        /**
         *
         */
        addPropertiesToRecipe: function (arrayOfIngredientsAndSteps) {
            this.errors = [];
            this.newRecipe.ingredients = RecipesRepository.getIngredients(arrayOfIngredientsAndSteps);
            this.newRecipe.steps = RecipesRepository.getSteps(arrayOfIngredientsAndSteps);
            this.newRecipe.ingredients = RecipesRepository.convertIngredientStringsToObjects(this.newRecipe.ingredients);
            this.errors = RecipesRepository.checkIngredientsForErrors(this.newRecipe.ingredients);
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

    }
});
