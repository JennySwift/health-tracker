angular.module('tracker')
    .factory('FoodEntriesFactory', function ($http) {
        return {
            insertMenuEntry: function ($sql_date, $new_entry) {
                //for logging a food. there is a separate function if we are logging a recipe.
                var $url = 'insert/menuEntry';

                var $data = {
                    date: $sql_date,
                    new_entry: $new_entry,
                };

                $("#menu").val("").focus();

                return $http.post($url, $data);
            },

            deleteFoodEntry: function ($id, $sql_date) {
                if (confirm("Are you sure you want to delete this entry?")) {
                    var $url = 'delete/foodEntry';
                    var $data = {
                        id: $id,
                        date: $sql_date
                    };

                    return $http.post($url, $data);
                }
            }
        }
    });