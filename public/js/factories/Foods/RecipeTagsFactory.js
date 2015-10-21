app.factory('RecipeTagsFactory', function ($http) {
    return {
        insertRecipeTag: function () {
            var $name = $("#create-new-recipe-tag").val();
            var $url = 'insert/recipeTag';
            var $data = {
                name: $name
            };

            $("#create-new-recipe-tag").val("");

            return $http.post($url, $data);
        },

        deleteRecipeTag: function ($id) {
            if (confirm("Are you sure you want to delete this tag?")) {
                var $url = 'delete/recipeTag';
                var $data = {
                    id: $id
                };

                return $http.post($url, $data);
            }
        },
    };
});