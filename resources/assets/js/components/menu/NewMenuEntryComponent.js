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

            //if (this.temporaryRecipePopup.contents) {
            //    this.temporaryRecipePopup.contents.length = 0;
            //}

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
    events: {
        'option-chosen': function (option) {
            this.newIngredient.food = option;
            this.newIngredient.unit = option.defaultUnit.data;
        }
    },
    ready: function () {

    }
});