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

            var arrayOfIngredientsAndSteps = RecipesRepository.getArrayOfIngredientsAndSteps();

            this.addPropertiesToRecipe(arrayOfIngredientsAndSteps);
            RecipesRepository.modifyQuickRecipeHtml(arrayOfIngredientsAndSteps);
            this.checkForAndHandleErrors();

            if (this.errors.length < 1) {
                //Prompt the user for the recipe name
                this.newRecipe.name = prompt('name your recipe');

                //If the user changes their mind and cancels
                if (!this.newRecipe.name) {
                    return;
                }

                this.checkForSimilarNames();
            }
            else {
                $("#quick-recipe-errors").show();
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
            store.showLoading();

            var data = {
                ingredients: this.newRecipe.ingredients
            };

            this.$http.get('/api/quickRecipes/checkForSimilarNames', data).then(function (response) {
                store.hideLoading();
                this.similarNames = response;

                if (response.units || response.foods) {
                    $.event.trigger('provide-feedback', ['Similar names were found', 'success']);
                    this.showPopup = true;
                }
                else {
                    this.insertRecipe();
                }

            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertRecipe: function () {
            store.showLoading();
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

            this.$http.post('/api/quickRecipes', data).then(function (response) {
                $.event.trigger('provide-feedback', ['Recipe created', 'success']);
                store.hideLoading();
                this.recipes.push(response.data);
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        chooseCorrectFoodName: function () {
            var that = this;
            $(this.similarNames.foods).each(function () {
                if (this.selected === this.existingFood.name) {
                    // Use the existing food rather than creating a new food.
                    that.newRecipe.ingredients[this.index].food = this.existingFood.name;
                }
            });
        },

        /**
         *
         */
        chooseCorrectUnitName: function () {
            var that = this;
            $(this.newRecipe.similarNames.units).each(function () {
                if (this.selected === this.existingUnit.name) {
                    //Use the existing unit rather than creating a new unit
                     that.newRecipe.ingredients[this.index].unit = this.existingUnit.name;
                }
            });
        },

        /**
         *
         */
        checkForAndHandleErrors: function () {
            this.errors = [];
            this.errors = RecipesRepository.checkIngredientsForErrors(this.newRecipe.ingredients);
        },

        /**
         *
         */
        addPropertiesToRecipe: function (arrayOfIngredientsAndSteps) {
            this.newRecipe.ingredients = RecipesRepository.getIngredients(arrayOfIngredientsAndSteps);
            this.newRecipe.steps = RecipesRepository.getSteps(arrayOfIngredientsAndSteps);
            this.newRecipe.ingredients = RecipesRepository.convertIngredientStringsToObjects(this.newRecipe.ingredients);
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
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'recipes'
    ],
    ready: function () {

    }
});
