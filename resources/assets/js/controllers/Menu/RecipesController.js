var app = angular.module('tracker');

(function () {
    app.controller('RecipesController', function ($rootScope, $scope, $http, FoodsFactory, FoodUnitsFactory, QuickRecipeFactory, AutocompleteFactory, RecipesFactory) {

        $scope.recipes = {
            all: recipes,
            filtered: recipes
        };
        $scope.tags = recipe_tags;

        $scope.selected = {};
        $scope.errors = {};

        //show
        //$scope.show = {
        //    autocomplete_options: {
        //        menu_items: false,
        //        foods: false,
        //    },
        //    popups: {
        //        recipe: false,
        //        similar_names: false
        //    },
        //    help: {
        //        quick_recipe: false
        //    }
        //};

        //filter
        $scope.filter = {
            recipes: {
                tag_ids: []
            }
        };

        $scope.recipe_popup = {};

        $scope.foods = {}; //all foods
        $scope.menu = {};//all foods and all recipes
        $scope.calories = {};
        $scope.quick_recipe = {};

        $scope.new_item = {
            recipe: {}
        };

        $scope.$watchCollection('filter.recipes.tag_ids', function (newValue, oldValue) {
            if (newValue !== oldValue) {
                $scope.filterRecipes();
            }
        });

        $(".wysiwyg").wysiwyg();

        $scope.filterRecipes = function () {
            RecipesFactory.filterRecipes($scope.filter.recipes.tag_ids).then(function (response) {
                $scope.recipes.filtered = response.data;
            });
        };

        $scope.getMenu = function () {
            if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
                $scope.menu = select.getMenu($scope.foods, $scope.recipes);
            }
        };


        /**
         * Deletes tags from the recipe then adds the correct ones
         */
        $scope.insertTagsIntoRecipe = function () {
            $scope.recipe_popup.notification = 'Saving tags...';
            RecipesFactory.insertTagsIntoRecipe($scope.recipe_popup.recipe.id, $scope.recipe_popup.tags).then(function (response) {
                $scope.recipe_popup.notification = 'Tags have been saved.';
                $scope.recipes.filtered = response.data;
            });
        };

        $scope.insertRecipe = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }

            $rootScope.showLoading();
            RecipesFactory.insert($scope.new_item.recipe.name)
                .then(function (response) {
                    $scope.recipes.filtered.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Recipe created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };


        /**
         * Add a unit to a food or remove the unit from the food.
         * The method name is old and should probably be changed.
         * @param $unit_id
         */
        $scope.insertOrDeleteUnitInCalories = function ($unit_id) {
            //Check if the checkbox is checked
            if ($scope.food_popup.food_units.indexOf($unit_id) === -1) {
                //It is now unchecked. Remove the unit from the food.
                FoodsFactory.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
            else {
                // It is now checked. Add the unit to the food.
                FoodsFactory.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
        };

        /**
         * update
         */

        $scope.updateRecipeMethod = function () {
            //this is some duplication of insertRecipeMethod
            var $string = $("#edit-recipe-method").html();
            var $lines = QuickRecipeFactory.formatString($string, $("#edit-recipe-method")).items;
            var $steps = [];

            $($lines).each(function () {
                var $line = this;
                $steps.push($line);
            });

            RecipesFactory.updateRecipeMethod($scope.recipe_popup.recipe.id, $steps).then(function (response) {
                $scope.recipe_popup = response.data;
                $scope.recipe_popup.edit_method = false;
            });
        };

        $scope.updateCalories = function ($keycode, $unit_id, $calories) {
            if ($keycode === 13) {
                FoodsFactory.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
                    $scope.food_popup = response.data;
                });
            }
        };

        $scope.updateDefaultUnit = function ($food_id, $unit_id) {
            FoodUnitsFactory.updateDefaultUnit($food_id, $unit_id).then(function (response) {
                $scope.food_popup = response.data;
            });
        };

        /**
         * delete
         */

        $scope.deleteFoodFromRecipe = function ($food_id) {
            RecipesFactory.deleteFoodFromRecipe($food_id, $scope.recipe_popup.recipe.id).then(function (response) {
                $scope.recipe_popup = response.data;
            });
        };

        $scope.deleteRecipe = function ($recipe) {
            $rootScope.showLoading();
            RecipesFactory.destroy($recipe)
                .then(function (response) {
                    $scope.recipes.filtered = _.without($scope.recipes.filtered, $recipe);
                    $rootScope.$broadcast('provideFeedback', 'Recipe deleted');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * popups
         */

        $scope.showRecipePopup = function ($recipe) {
            // $scope.selected.recipe = $recipe;
            $rootScope.showLoading();
            RecipesFactory.show($recipe)
                .then(function (response) {
                    $scope.show.popups.recipe = true;
                    $scope.recipe_popup.recipe = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * autocomplete food (for adding food to a recipe in the recipe popup)
         */

        $scope.autocompleteFood = function ($keycode) {
            var $typing = $("#recipe-popup-food-input").val();
            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                //fill the dropdown
                AutocompleteFactory.food($typing).then(function (response) {
                    $scope.recipe_popup.autocomplete_options = response.data;
                    //show the dropdown
                    $scope.show.autocomplete_options.foods = true;
                    //select the first item
                    $scope.recipe_popup.autocomplete_options[0].selected = true;
                });
            }
            else if ($keycode === 38) {
                //up arrow pressed
                AutocompleteFactory.autocompleteUpArrow($scope.recipe_popup.autocomplete_options);

            }
            else if ($keycode === 40) {
                //down arrow pressed
                AutocompleteFactory.autocompleteDownArrow($scope.recipe_popup.autocomplete_options);
            }
        };

        $scope.finishFoodAutocomplete = function ($array, $set_focus) {
            //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
            var $selected = _.findWhere($array, {selected: true});
            $scope.recipe_popup.food = $selected;
            $scope.selected.food = $selected;
            $scope.show.autocomplete_options.foods = false;
            $($set_focus).val("").focus();
        };

        $scope.insertOrAutocompleteFoodEntry = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            //enter is pressed
            if ($scope.show.autocomplete_options.foods) {
                //enter is for the autocomplete
                $scope.finishFoodAutocomplete($scope.recipe_popup.autocomplete_options, $("#recipe-popup-food-quantity"));
            }
            else {
                // if enter is to add the entry
                $scope.insertFoodIntoRecipe();
            }
        };

        $scope.insertFoodIntoRecipe = function () {
            //we are adding a food to a permanent recipe
            var $data = {
                recipe_id: $scope.recipe_popup.recipe.id,
                food_id: $scope.selected.food.id,
                unit_id: $("#recipe-popup-unit").val(),
                quantity: $scope.recipe_popup.food.quantity,
                description: $scope.recipe_popup.food.description
            };

            RecipesFactory.insertFoodIntoRecipe($data).then(function (response) {
                $scope.recipe_popup = response.data;
            });
            $("#recipe-popup-food-input").val("").focus();
            $scope.recipe_popup.food.description = "";
        };

        /**
         * other
         */

        $scope.toggleQuickRecipeHelp = function () {
            $scope.showHelp = !$scope.showHelp;
        };

        $scope.toggleEditMethod = function () {
            //Toggle the visibility of the wysywig
            $scope.recipe_popup.edit_method = !$scope.recipe_popup.edit_method;

            //If we are editing the recipe, prepare the html of the wysiwyg
            if ($scope.recipe_popup.edit_method) {
                var $text;
                var $string = "";

                //convert the array into a string so I can make the wysiwyg display the steps
                $($scope.recipe_popup.steps).each(function () {
                    $text = this.text;
                    $text = $text + '<br>';
                    // $text = '<div>' + $text + '</div>';
                    $string+= $text;
                });
                $("#edit-recipe-method").html($string);
            }
        };

    });

})();