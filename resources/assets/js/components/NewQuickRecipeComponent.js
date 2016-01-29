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
            checkForSimilarNames: true
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
        insertRecipeIfNoSimilarNames: function () {
            //remove any previous error styling so it doesn't wreck up the html
            $("#quick-recipe > *").removeAttr("style");

            //Empty the errors array from any previous attempts
            this.errors = [];

            //Hide the errors div because even with emptying the scope property,
            // the display is slow to update.
            $("#quick-recipe-errors").hide();

            var arrayOfIngredientsAndSteps = RecipesRepository.getArrayOfIngredientsAndSteps();

            this.newRecipe.ingredients = RecipesRepository.getIngredients(arrayOfIngredientsAndSteps);
            this.newRecipe.steps = RecipesRepository.getSteps(arrayOfIngredientsAndSteps);

            this.newRecipe.ingredients = RecipesRepository.convertIngredientStringsToObjects(this.newRecipe.ingredients);

            this.errors = RecipesRepository.checkIngredientsForErrors(this.newRecipe.ingredients);

            if (this.errors.length < 1) {
                //Prompt the user for the recipe name
                this.newRecipe.name = prompt('name your recipe');

                //If the user changes their mind and cancels
                if (!this.newRecipe.name) {
                    return;
                }

                this.quickRecipeAttemptInsert();
            }
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
         * Attempt to insert the recipe.
         * It won't be inserted if similar names are found.
         */
        quickRecipeAttemptInsert: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newRecipe.name,
                ingredients: this.newRecipe.ingredients,
                steps: this.newRecipe.steps,
                checkForSimilarNames: this.checkForSimilarNames
            };

            this.$http.post('/api/quickRecipes', data, function (response) {
                    $.event.trigger('hide-loading');

                    if (response.similarNames) {
                        $.event.trigger('provide-feedback', ['Similar names were found', 'success']);
                        //this.newRecipe.similarNames = response.similarNames;
                        this.similarNames = response.similarNames;
                        this.showPopup = true;
                    }
                    else {
                        this.recipes.push(response);
                    }
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         * This is for entering the recipe after the similar name check is done.
         * We call insertQuickRecipe again,
         * but this time with $checkSimilarNames parameter as false,
         * so that the recipe gets entered.
         */
        insertRecipeWithoutCheckingForSimilarNames: function () {
            this.showPopup = false;
            this.doTheFoods();
            this.doTheUnits();
            this.insertQuickRecipe();
        },

        doTheFoods: function () {
            $(this.newRecipe.similarNames.foods).each(function () {
                if (this.checked === this.existingFood.name) {
                    // We are using the existing food rather than creating a new food.
                    // Therefore, change $scope.quick_recipe.contents
                    // to use the correct food name.
                    this.newRecipe.ingredients[this.index].food = this.existingFood.name;
                }
            });
        },

        doTheUnits: function () {
            $(this.newRecipe.similarNames.units).each(function () {
                if (this.checked === this.existingUnit.name) {
                    //we are using the existing unit rather than creating
                    //a new unit. therefore, change $scope.quick_recipe.contents
                    // to use the correct unit name.
                    this.newRecipe.ingredients[this.index].unit = this.existingUnit.name;
                }
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

    }
});
