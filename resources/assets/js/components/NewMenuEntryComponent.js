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
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        //when autocomplete changes, this.newIngredient.unit = this.newIngredient[this.autocompleteField].defaultUnit.data;

        /**
         *
         */
        insertMenuEntry: function (ingredient) {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                food_id: ingredient.food.id,
                unit_id: ingredient.unit.id,
                quantity: ingredient.quantity,
            };

            $.event.trigger('get-entries');

            if (this.temporaryRecipePopup.contents) {
                this.temporaryRecipePopup.contents.length = 0;
            }

            $("#menu").val("").focus();

            this.$http.post('/api/menuEntries', data, function (response) {
                this.menuEntries.push(response);
                this.newIngredient.description = '';
                this.newIngredient.quantity = '';
                $("#new-ingredient-food-name").focus();
                $.event.trigger('provide-feedback', ['Menu entry created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertRecipeEntry: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                recipe_id: this.selected.menu.id,
                recipe_contents: this.temporaryRecipePopup.contents
            };

            this.$http.post('/insert/recipeEntry', data, function (response) {
                    this.entries.menu = response.data;
                    this.show.popups.temporary_recipe = false;
                    $("#menu").val("").focus();
                    $.event.trigger('provide-feedback', ['Recipe created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        showTemporaryRecipePopup: function () {
            this.show.popups.temporary_recipe = true;
            FoodsFactory.getRecipeContents(this.selected.menu.id).then(function (response) {
                this.temporaryRecipePopup = response.data;

                $(this.temporaryRecipePopup.contents).each(function () {
                    this.original_quantity = this.quantity;
                });
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
    ready: function () {

    }
});