module.exports = {
    template: '#new-menu-entry-template',
    data: function () {
        return {
            newIngredient: {
                food: {
                    units: {
                        data: []
                    },
                    defaultUnit: {
                        data: {}
                    }
                },
                unit: {},
                type: ''
            },
            recipeEntry: {},
            entryNumberForRecipe: 0
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertMenuEntry: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                food_id: this.newIngredient.food.id,
                unit_id: this.newIngredient.unit.id,
                quantity: this.newIngredient.quantity,
            };

            $.event.trigger('get-entries');

            $("#new-menu-entry-food").focus();

            this.$http.post('/api/menuEntries', data).then(function (response) {
                this.newIngredient.description = '';
                this.newIngredient.quantity = '';
                $("#new-ingredient-food-name").focus();
                $.event.trigger('provide-feedback', ['Menu entry created', 'success']);
                $.event.trigger('menu-entry-added', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param ingredient
         */
        insertEntry: function (ingredient) {
            var data = {
                date: this.date.sql,
                food_id: ingredient.food.data.id,
                recipe_id: this.recipeEntry.id,
                unit_id: ingredient.unit.data.id,
                quantity: ingredient.quantity,
            };

            this.$http.post('/api/menuEntries', data).then(function (response) {
                //This adds the entry to the entries with the JS
                $.event.trigger('menu-entry-added', [response]);
                this.entryNumberForRecipe++;
                //If it's the last of the entries for the recipe being added, do stuff
                if (this.entryNumberForRecipe == this.recipeEntry.ingredients.data.length) {
                    $.event.trigger('provide-feedback', ['Recipe entries created', 'success']);
                    //I think this just updates the calorie info for the day
                    $.event.trigger('get-entries');
                    $.event.trigger('hide-loading');
                }
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * Insert each entry for a recipe, one at a time
         * @param recipe
         */
        insertEntriesForRecipe: function (recipe) {
            $.event.trigger('show-loading');

            this.entryNumberForRecipe = 0;
            this.recipeEntry = recipe;

            for (var i = 0; i < recipe.ingredients.data.length; i++) {
                this.insertEntry(recipe.ingredients.data[i]);
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('insert-entries-for-recipe', function (event, recipe) {
                that.insertEntriesForRecipe(recipe);
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
        'date'
    ],
    events: {
        'option-chosen': function (option) {
            if (option.type === 'food') {
                this.newIngredient.food = option;
                this.newIngredient.type = 'food';
                if (option.defaultUnit) {
                    this.newIngredient.unit = option.defaultUnit.data;
                }
            }
            else if (option.type === 'recipe') {
                this.newIngredient = option;
                $.event.trigger('show-temporary-recipe-popup', [option]);
            }
        }
    },
    ready: function () {
        this.listen();
    }
};