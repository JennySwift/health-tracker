angular.module('tracker')
    .factory('RecipesFactory', function ($http) {
        return {
            filterRecipes: function ($tag_ids) {
                var $typing = $("#filter-recipes").val();
                var $url = 'select/filterRecipes';
                var $table = "recipes";

                var $data = {
                    typing: $typing,
                    tag_ids: $tag_ids
                };

                return $http.post($url, $data);
            },
            insertRecipe: function ($name) {
                var $url = 'insert/recipe';
                var $data = {
                    name: $name
                };

                return $http.post($url, $data);
            },
            insertQuickRecipe: function ($recipe, $check_similar_names) {
                var $url = 'insert/quickRecipe';
                var $data = {
                    recipe: $recipe,
                    check_similar_names: $check_similar_names
                };

                return $http.post($url, $data);
            },
            getRecipeContents: function ($recipe_id) {
                var $url = 'select/recipeContents';

                var $data = {
                    recipe_id: $recipe_id
                };

                return $http.post($url, $data);
            },
            insertFoodIntoRecipe: function ($data) {
                var $url = 'insert/foodIntoRecipe';

                return $http.post($url, $data);
            },
            insertTagsIntoRecipe: function ($recipe_id, $tags) {
                var $url = 'insert/tagsIntoRecipe';
                var $data = {
                    recipe_id: $recipe_id,
                    tags: $tags
                };

                return $http.post($url, $data);
            },
            /**
             * Deletes the existing method then inserts the edited method
             * @param $recipe_id
             * @param $steps
             * @returns {*}
             */
            updateRecipeMethod: function ($recipe_id, $steps) {
                var $url = 'update/recipeMethod';
                var $data = {
                    recipe_id: $recipe_id,
                    steps: $steps
                };

                return $http.post($url, $data);
            },
            deleteFoodFromRecipe: function ($food_id, $recipe_id) {
                if (confirm("Are you sure you want to remove this food from your recipe?")) {
                    var $url = 'delete/foodFromRecipe';
                    var $data = {
                        food_id: $food_id,
                        recipe_id: $recipe_id
                    };

                    return $http.post($url, $data);
                }
            },
            deleteRecipe: function ($id) {
                if (confirm("Are you sure you want to delete this recipe?")) {
                    var $url = 'delete/recipe';
                    var $data = {
                        id: $id
                    };

                    return $http.post($url, $data);
                }
            },
        }
    });