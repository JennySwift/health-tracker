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
                    this.menuEntries = _.without(this.menuEntries, entry);
                    $.event.trigger('provide-feedback', ['MenuEntry deleted', 'success']);
                    $.event.trigger('menu-entry-deleted');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
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
            $(document).on('menu-entry-added', function (event, entry) {
                $.event.trigger('show-loading');
                if (entry.date === that.date.sql) {
                    that.menuEntries.push(entry)
                }
            });
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
            });
            //$(document).on('get-menu-entries', function (event) {
            //    that.getEntriesForTheDay();
            //});
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


