var MenuEntriesComponent = Vue.component('menu-entries', {
    template: '#menu-entries-template',
    data: function () {
        return {
            menuEntries: menuEntries,
            temporaryRecipePopup: {},
            selected: {
                dropdown_item: {},
                food: {},
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        deleteMenuEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/menuEntries/' + entry.id, function (response) {
                    //Todo: no need to get all the entries here.
                    // Just remove the one that was deleted, while still fetching the other info such as calories for the day
                    $.event.trigger('get-entries');
                    $.event.trigger('provide-feedback', ['MenuEntry deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

        /**
         * autocomplete temporary recipe food
         */

        autocompleteTemporaryRecipeFood: function ($keycode) {
            var $typing = $("#temporary-recipe-food-input").val();

            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                //fill the dropdown
                AutocompleteFactory.food($typing).then(function (response) {
                    this.autocomplete_options.temporary_recipe_foods = response.data;
                    //show the dropdown
                    this.showAutocompleteOptions.temporary_recipe_foods = true;
                    //select the first item
                    this.autocomplete_options.temporary_recipe_foods[0].selected = true;
                });
            }
            else if ($keycode === 38) {
                //up arrow pressed
                AutocompleteFactory.autocompleteUpArrow(this.autocomplete_options.temporary_recipe_foods);

            }
            else if ($keycode === 40) {
                //down arrow pressed
                AutocompleteFactory.autocompleteDownArrow(this.autocomplete_options.temporary_recipe_foods);
            }
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
        deleteFromTemporaryRecipe: function ($item) {
            this.temporaryRecipePopup.contents = _.without(this.temporaryRecipePopup.contents, $item);
        },

        showDeleteFoodOrRecipeEntryPopup: function ($entry_id, $recipe_id) {
            this.show.popups.delete_food_or_recipe_entry = true;
            this.selected.entry = {
                id: $entry_id
            };
            this.selected.recipe = {
                id: $recipe_id
            };
        },

        //Todo: get food entries, calories for the day and calories for the week
        //after deleting
        //deleteRecipeEntry: function () {
        //    RecipeEntriesFactory.deleteRecipeEntry(this.date.sql, this.selected.recipe.id).then(function (response) {
        //        this.entries.menu = response.data.food_entries;
        //        this.calories.day = response.data.calories_for_the_day;
        //        this.calories.week_avg = response.data.calories_for_the_week;
        //        this.show.popups.delete_food_or_recipe_entry = false;
        //    });
        //},

        /**
        *
        */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/menuEntries/' + this.date.sql, function (response) {
                this.menuEntries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            //$(document).on('get-food-entries', function (event) {
            //    $.event.trigger('show-loading');
            //    that.getEntriesForTheDay();
            //});
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
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
        this.listen();
    }
});

//this.$watch('recipe.portion', function (newValue, oldValue) {
//    $(this.temporaryRecipePopup.contents).each(function () {
//        if (this.original_quantity) {
//            //making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
//            this.quantity = this.original_quantity * newValue;
//        }
//    });
//});


