angular.module('tracker')
    .controller('QuickRecipeController', function ($rootScope, $scope, QuickRecipeFactory, RecipesFactory) {

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
        $scope.quickRecipe = function () {
            //remove any previous error styling so it doesn't wreck up the html
            $("#quick-recipe > *").removeAttr("style");

            //Empty the errors array from any previous attempts
            $scope.errors.quick_recipe = [];

            //Hide the errors div because even with emptying the scope property,
            // the display is slow to update.
            $("#quick-recipe-errors").hide();

            var $string = $("#quick-recipe").html();

            //Recipe is an object, with an array of items and an array of steps.
            var $recipe = QuickRecipeFactory.formatString($string, $("#quick-recipe"));

            $recipe.items = QuickRecipeFactory.populateItemsArray($recipe.items);

            //check item contains quantity, unit and food
            //and convert quantities to decimals if necessary
            var $items_and_errors = QuickRecipeFactory.errorCheck($recipe.items);

            if ($items_and_errors.errors.length > 0) {
                $scope.errors.quick_recipe = $items_and_errors.errors;
                $("#quick-recipe-errors").show();
                return;
            }

            //Prompt the user for the recipe name
            var $recipe_name = prompt('name your recipe');

            //If the user changes their mind and cancels
            if (!$recipe_name) {
                return;
            }

            $recipe = {
                name: $recipe_name,
                ingredients: $items_and_errors.items,
                steps: $recipe.method
            };

            $scope.quick_recipe = $recipe;

            quickRecipeAttemptInsert($recipe);
        };

        /**
         * Attempt to insert the recipe.
         * It won't be inserted if similar names are found.
         * @param $recipe
         */
        function quickRecipeAttemptInsert ($recipe) {
            $rootScope.showLoading();
            RecipesFactory.insertQuickRecipe($recipe, true)
                .then(function (response) {
                    if (response.data.similar_names) {
                        $rootScope.$broadcast('provideFeedback', 'Similar names were found');
                        $scope.quick_recipe.similar_names = response.data.similar_names;
                        $scope.show.popups.similar_names = true;
                    }
                    else {
                        $rootScope.$broadcast('provideFeedback', 'Recipe created');
                        $scope.recipes.filtered.push(response.data.data);
                    }

                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }


        /**
         * This is for entering the recipe after the similar name check is done.
         * We call RecipesFactory.insertQuickRecipe again,
         * but this time with $check_similar_names parameter as false,
         * so that the recipe gets entered.
         */
        $scope.quickRecipeFinish = function () {
            $scope.show.popups.similar_names = false;

            doTheFoods();
            doTheUnits();
            insertQuickRecipe();
        };

        function doTheFoods () {
            $($scope.quick_recipe.similar_names.foods).each(function () {
                if (this.checked === this.existing_food.name) {
                    // We are using the existing food rather than creating a new food.
                    // Therefore, change $scope.quick_recipe.contents
                    // to use the correct food name.
                    $scope.quick_recipe.items[this.index].food = this.existing_food.name;
                }
            });
        }

        function doTheUnits () {
            $($scope.quick_recipe.similar_names.units).each(function () {
                if (this.checked === this.existing_unit.name) {
                    //we are using the existing unit rather than creating
                    //a new unit. therefore, change $scope.quick_recipe.contents
                    // to use the correct unit name.
                    $scope.quick_recipe.items[this.index].unit = this.existing_unit.name;
                }
            });
        }

        function insertQuickRecipe () {
            $rootScope.showLoading();
            RecipesFactory.insertQuickRecipe($scope.quick_recipe, false)
                .then(function (response) {
                    $scope.recipes.filtered = response.data.recipes;
                    $rootScope.$broadcast('provideFeedback', 'Recipe created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

    });