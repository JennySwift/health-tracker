angular.module('tracker')
    .controller('MenuEntriesController', function ($scope, FoodEntriesFactory, RecipeEntriesFactory) {

        //$scope.$watch('recipe.portion', function (newValue, oldValue) {
        //    $($scope.temporary_recipe_popup.contents).each(function () {
        //        if (this.original_quantity) {
        //            //making sure we don't alter the quantity of a food that has been added to the temporary recipe (by doing the if check)
        //            this.quantity = this.original_quantity * newValue;
        //        }
        //    });
        //});

        $scope.insertMenuEntry = function () {
            $scope.new_entry.food.id = $scope.selected.food.id;
            $scope.new_entry.food.name = $scope.selected.food.name;
            $scope.new_entry.food.unit_id = $("#food-unit").val();

            FoodEntriesFactory.insertMenuEntry($scope.date.sql, $scope.new_entry.food).then(function (response) {
                $scope.entries.menu = response.data.food_entries;
                $scope.calories.day = response.data.calories_for_the_day;
                $scope.calories.week_avg = response.data.calories_for_the_week;

                if ($scope.temporary_recipe_popup.contents) {
                    $scope.temporary_recipe_popup.contents.length = 0;
                }
                $scope.loading = false;
            });
        };

        $scope.insertRecipeEntry = function () {
            RecipeEntriesFactory.insertRecipeEntry($scope.date.sql, $scope.selected.menu.id, $scope.temporary_recipe_popup.contents).then(function (response) {
                $scope.entries.menu = response.data;
                $scope.show.popups.temporary_recipe = false;
            });
        };

        $scope.deleteFoodEntry = function ($entry_id) {
            $entry_id = $entry_id || $scope.selected.entry.id;

            FoodEntriesFactory.deleteFoodEntry($entry_id, $scope.date.sql).then(function (response) {
                $scope.entries.menu = response.data.food_entries;
                $scope.calories.day = response.data.calories_for_the_day;
                $scope.calories.week_avg = response.data.calories_for_the_week;
                $scope.show.popups.delete_food_or_recipe_entry = false;
            });
        };

        //Todo: get food entries, calories for the day and calories for the week
        //after deleting
        $scope.deleteRecipeEntry = function () {
            RecipeEntriesFactory.deleteRecipeEntry($scope.date.sql, $scope.selected.recipe.id).then(function (response) {
                $scope.entries.menu = response.data.food_entries;
                $scope.calories.day = response.data.calories_for_the_day;
                $scope.calories.week_avg = response.data.calories_for_the_week;
                $scope.show.popups.delete_food_or_recipe_entry = false;
            });
        };

        /**
         * autocomplete
         */

        /**
         * As user types in the input, populate the dropdown.
         * If user presses arrows, select the appropriate item in the dropdown.
         * If user presses enter, that is taken care of in $scope.insertOrAutocompleteMenuEntry.
         * @param $keycode
         */
        $scope.autocompleteMenu = function ($keycode) {
            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                AutocompleteFactory.menu().then(function (response) {
                    console.log(response);
                    //fill the dropdown
                    $scope.autocomplete_options.menu_items = response.data;
                    //show the dropdown
                    $scope.show.autocomplete_options.menu_items = true;
                    //select the first item
                    $scope.autocomplete_options.menu_items[0].selected = true;
                });
            }
            else if ($keycode === 38) {
                //up arrow pressed
                AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.menu_items);

            }
            else if ($keycode === 40) {
                //down arrow pressed
                AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.menu_items);
            }
        };

        /**
         * For when the user presses enter from any of the relevant input fields.
         * If enter is to complete the autocomplete, call appropriate functions.
         * If enter is to add an entry, call the appropriate function.
         * @param $keycode
         */
        $scope.insertOrAutocompleteMenuEntry = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            //enter is pressed
            if ($scope.show.autocomplete_options.menu_items) {
                //if enter is for the autocomplete
                $scope.finishMenuAutocomplete($scope.autocomplete_options.menu_items, $("#food-quantity"));

                if ($scope.selected.menu.type === 'recipe') {
                    $scope.showTemporaryRecipePopup();
                }
            }

            // enter is to add the entry
            else {
                $scope.insertMenuEntry();
            }
        };

        $scope.finishMenuAutocomplete = function ($array, $set_focus) {
            //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
            var $selected = _.findWhere($array, {selected: true});
            $scope.selected.food = $selected;
            $scope.new_entry.menu = $selected;
            $scope.selected.menu = $selected;
            $scope.show.autocomplete_options.menu_items = false;
            $($set_focus).val("").focus();
        };

        /**
         * autocomplete temporary recipe food
         */

        $scope.autocompleteTemporaryRecipeFood = function ($keycode) {
            var $typing = $("#temporary-recipe-food-input").val();

            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                //fill the dropdown
                AutocompleteFactory.food($typing).then(function (response) {
                    $scope.autocomplete_options.temporary_recipe_foods = response.data;
                    //show the dropdown
                    $scope.show.autocomplete_options.temporary_recipe_foods = true;
                    //select the first item
                    $scope.autocomplete_options.temporary_recipe_foods[0].selected = true;
                });
            }
            else if ($keycode === 38) {
                //up arrow pressed
                AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.temporary_recipe_foods);

            }
            else if ($keycode === 40) {
                //down arrow pressed
                AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.temporary_recipe_foods);
            }
        };

        $scope.finishTemporaryRecipeFoodAutocomplete = function ($array, $set_focus) {
            //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
            var $selected = _.findWhere($array, {selected: true});
            $scope.temporary_recipe_popup.food = $selected;
            $scope.selected.food = $selected;
            $scope.show.autocomplete_options.temporary_recipe_foods = false;
            $($set_focus).val("").focus();
        };

        $scope.insertOrAutocompleteTemporaryRecipeFood = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            //enter is pressed
            if ($scope.show.autocomplete_options.temporary_recipe_foods) {
                //enter is for the autocomplete
                $scope.finishTemporaryRecipeFoodAutocomplete($scope.autocomplete_options.temporary_recipe_foods, $("#temporary-recipe-popup-food-quantity"));
                // $scope.displayAssocUnitOptions();
            }
            else {
                // if enter is to add the entry
                $scope.insertFoodIntoTemporaryRecipe();
            }
        };

        $scope.insertFoodIntoTemporaryRecipe = function () {
            //we are adding a food to a temporary recipe
            var $unit_name = $("#temporary-recipe-popup-unit option:selected").text();
            $scope.temporary_recipe_popup.contents.push({
                "food_id": $scope.temporary_recipe_popup.food.id,
                "name": $scope.temporary_recipe_popup.food.name,
                "quantity": $scope.temporary_recipe_popup.quantity,
                "unit_id": $("#temporary-recipe-popup-unit").val(),
                "unit_name": $unit_name,
                "units": $scope.temporary_recipe_popup.food.units
            });

            $("#temporary-recipe-food-input").val("").focus();
        };

        $scope.deleteFromTemporaryRecipe = function ($item) {
            $scope.temporary_recipe_popup.contents = _.without($scope.temporary_recipe_popup.contents, $item);
        };

        $scope.showTemporaryRecipePopup = function () {
            $scope.show.popups.temporary_recipe = true;
            FoodsFactory.getRecipeContents($scope.selected.menu.id).then(function (response) {
                $scope.temporary_recipe_popup = response.data;

                $($scope.temporary_recipe_popup.contents).each(function () {
                    this.original_quantity = this.quantity;
                });
            });
        };

        $scope.showDeleteFoodOrRecipeEntryPopup = function ($entry_id, $recipe_id) {
            $scope.show.popups.delete_food_or_recipe_entry = true;
            $scope.selected.entry = {
                id: $entry_id
            };
            $scope.selected.recipe = {
                id: $recipe_id
            };
        };

    });