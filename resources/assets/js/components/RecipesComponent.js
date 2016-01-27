var Recipes = Vue.component('recipes', {
    template: '#recipes-template',
    data: function () {
        return {
            newRecipe: {},
            recipesNameFilter: '',
            recipesTagFilter: ''
        };
    },
    components: {},
    filters: {
        recipesFilter: function (recipes) {
            var that = this;

            return recipes.filter(function (recipe) {
                var containsName = recipe.name.indexOf(that.recipesNameFilter) !== -1;
                var containsTags = true;
                var tagIdsForRecipe = _.pluck(recipe.tags.data, 'id');
                var count = 0;

                if (that.recipesTagFilter.length > 0) {
                    containsTags = false;
                    for (var i = 0; i < that.recipesTagFilter.length; i++) {
                        if (tagIdsForRecipe.indexOf(that.recipesTagFilter[i]) !== -1) {
                            //Recipe contains the tag
                            count++;
                        }
                    }
                    if (count === that.recipesTagFilter.length) {
                        containsTags = true;
                    }
                }

                return containsName && containsTags;
            });
        }
    },
    methods: {

        /**
         *
         * @param recipe
         */
        showRecipePopup: function (recipe) {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes/' + recipe.id, function (response) {
                    $.event.trigger('show-recipe-popup', [response]);
                    $.event.trigger('hide-loading');
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
            var data = {
                name: this.newRecipe.name
            };

            this.$http.post('/api/recipes', data, function (response) {
                this.recipes.push(response.data);
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
        showNewRecipeFields: function () {
            this.addingNewRecipe = true;
            this.editingRecipe = false;
        },

        /**
        *
        */
        deleteRecipe: function (recipe) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/recipes/' + recipe.id, function (response) {
                    this.recipes = _.without(this.recipes, recipe);
                    $.event.trigger('provide-feedback', ['Recipe deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
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
        'tags',
        'recipesTagFilter',
        'recipes'
    ],
    ready: function () {
        $(".wysiwyg").wysiwyg();
    }
});
















//$scope.getMenu = function () {
//    if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
//        $scope.menu = select.getMenu($scope.foods, $scope.recipes);
//    }
//};






//
//
///**
// * Add a unit to a food or remove the unit from the food.
// * The method name is old and should probably be changed.
// * @param $unit_id
// */
//$scope.insertOrDeleteUnitInCalories = function ($unit_id) {
//    //Check if the checkbox is checked
//    if ($scope.food_popup.food_units.indexOf($unit_id) === -1) {
//        //It is now unchecked. Remove the unit from the food.
//        FoodsFactory.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//    else {
//        // It is now checked. Add the unit to the food.
//        FoodsFactory.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//};
//
///**
// * update
// */
//

//
//$scope.updateCalories = function ($keycode, $unit_id, $calories) {
//    if ($keycode === 13) {
//        FoodsFactory.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//};
//
//$scope.updateDefaultUnit = function ($food_id, $unit_id) {
//    FoodUnitsFactory.updateDefaultUnit($food_id, $unit_id).then(function (response) {
//        $scope.food_popup = response.data;
//    });
//};
//
///**
// * delete
// */
//
//
//
///**
// * popups
// */
//
//
///**
// * autocomplete food (for adding food to a recipe in the recipe popup)
// */
//
//$scope.autocompleteFood = function ($keycode) {
//    var $typing = $("#recipe-popup-food-input").val();
//    if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
//        //not enter, up arrow or down arrow
//        //fill the dropdown
//        AutocompleteFactory.food($typing).then(function (response) {
//            $scope.recipe_popup.autocomplete_options = response.data;
//            //show the dropdown
//            $scope.show.autocomplete_options.foods = true;
//            //select the first item
//            $scope.recipe_popup.autocomplete_options[0].selected = true;
//        });
//    }
//    else if ($keycode === 38) {
//        //up arrow pressed
//        AutocompleteFactory.autocompleteUpArrow($scope.recipe_popup.autocomplete_options);
//
//    }
//    else if ($keycode === 40) {
//        //down arrow pressed
//        AutocompleteFactory.autocompleteDownArrow($scope.recipe_popup.autocomplete_options);
//    }
//};
//
//$scope.finishFoodAutocomplete = function ($array, $set_focus) {
//    //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
//    var $selected = _.findWhere($array, {selected: true});
//    $scope.recipe_popup.food = $selected;
//    $scope.selected.food = $selected;
//    $scope.show.autocomplete_options.foods = false;
//    $($set_focus).val("").focus();
//};
//
//$scope.insertOrAutocompleteFoodEntry = function ($keycode) {
//    if ($keycode !== 13) {
//        return;
//    }
//    //enter is pressed
//    if ($scope.show.autocomplete_options.foods) {
//        //enter is for the autocomplete
//        $scope.finishFoodAutocomplete($scope.recipe_popup.autocomplete_options, $("#recipe-popup-food-quantity"));
//    }
//    else {
//        // if enter is to add the entry
//        $scope.insertFoodIntoRecipe();
//    }
//};
//

//$scope.deleteFoodFromRecipe = function ($food_id) {
//    RecipesFactory.deleteFoodFromRecipe($food_id, $scope.recipe_popup.recipe.id).then(function (response) {
//        $scope.recipe_popup = response.data;
//    });
//};
//$scope.insertFoodIntoRecipe = function () {
//    //we are adding a food to a permanent recipe
//    var $data = {
//        recipe_id: $scope.recipe_popup.recipe.id,
//        food_id: $scope.selected.food.id,
//        unit_id: $("#recipe-popup-unit").val(),
//        quantity: $scope.recipe_popup.food.quantity,
//        description: $scope.recipe_popup.food.description
//    };
//
//    RecipesFactory.insertFoodIntoRecipe($data).then(function (response) {
//        $scope.recipe_popup = response.data;
//    });
//    $("#recipe-popup-food-input").val("").focus();
//    $scope.recipe_popup.food.description = "";
//};




//
///**
// * other
// */
//
//$scope.toggleQuickRecipeHelp = function () {
//    $scope.showHelp = !$scope.showHelp;
//};
//

