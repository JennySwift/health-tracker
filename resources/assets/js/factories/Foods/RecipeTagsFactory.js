app.factory('RecipeTagsFactory', function ($http) {
    return {
        insert: function () {
            var $name = $("#create-new-recipe-tag").val();
            var $url = 'api/recipeTags';
            var $data = {
                name: $name
            };

            $("#create-new-recipe-tag").val("");

            return $http.post($url, $data);
        },

        destroy: function ($tag) {
            if (confirm("Are you sure you want to delete this tag?")) {
                var $url = 'api/recipeTags/' + $tag.id;

                return $http.delete($url);
            }
        },
    };
});