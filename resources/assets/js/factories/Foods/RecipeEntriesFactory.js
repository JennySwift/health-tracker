angular.module('tracker')
    .factory('RecipeEntriesFactory', function ($http) {
        return {
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