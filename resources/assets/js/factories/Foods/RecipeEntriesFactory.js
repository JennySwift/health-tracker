angular.module('tracker')
    .factory('RecipeEntriesFactory', function ($http) {
        return {
            insertRecipeEntry: function ($sql_date, $recipe_id, $recipe_contents) {
                var $url = 'insert/recipeEntry';

                var $data = {
                    date: $sql_date,
                    recipe_id: $recipe_id,
                    recipe_contents: $recipe_contents
                };

                $("#menu").val("").focus();

                return $http.post($url, $data);
            },
            deleteRecipeEntry: function ($sql_date, $recipe_id) {
                var $url = 'delete/recipeEntry';
                var $data = {
                    recipe_id: $recipe_id,
                    date: $sql_date
                };

                return $http.post($url, $data);
            }

        }
    });