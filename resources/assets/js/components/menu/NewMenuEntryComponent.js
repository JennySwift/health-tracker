var NewMenuEntry = Vue.component('new-menu-entry', {
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
            }
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

            this.$http.post('/api/menuEntries', data, function (response) {
                this.newIngredient.description = '';
                this.newIngredient.quantity = '';
                $("#new-ingredient-food-name").focus();
                $.event.trigger('provide-feedback', ['Menu entry created', 'success']);
                $.event.trigger('menu-entry-added', [response]);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param ingredient
         * @param recipeId
         */
        insertEntry: function (ingredient, recipeId) {
            var data = {
                date: this.date.sql,
                food_id: ingredient.food.data.id,
                recipe_id: recipeId,
                unit_id: ingredient.unit.data.id,
                quantity: ingredient.quantity,
            };

            this.$http.post('/api/menuEntries', data, function (response) {
                $.event.trigger('provide-feedback', ['Recipe entries created', 'success']);
                //$.event.trigger('menu-entry-added', [response]);
                //$.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * Insert each entry for a recipe, one at a time
         * @param recipe
         */
        insertEntriesForRecipe: function (recipe) {
            console.log(recipe);
            $.event.trigger('show-loading');

            for (var i = 0; i < recipe.ingredients.data.length; i++) {
                this.insertEntry(recipe.ingredients.data[i], recipe.id);
            }
            $.event.trigger('hide-loading');
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
            this.$broadcast('response-error', response);
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
});