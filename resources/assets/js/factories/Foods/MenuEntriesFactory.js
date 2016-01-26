angular.module('tracker')
    .factory('MenuEntriesFactory', function ($http) {
        return {
            getEntriesForTheDay: function ($date) {
                var $url = 'api/menuEntries/' + $date;
                return $http.get($url);
            },
            insertMenuEntry: function ($sql_date, $new_entry) {
                //for logging a food. there is a separate function if we are logging a recipe.
                var $url = '/api/menuEntries';

                var $data = {
                    date: $sql_date,
                    food_id: $new_entry.id,
                    unit_id: $("#food-unit").val(),
                    quantity: $("#food-quantity").val(),
                };

                $("#menu").val("").focus();

                return $http.post($url, $data);
            },

            deleteFoodEntry: function ($id, $sql_date) {
                if (confirm("Are you sure you want to delete this entry?")) {
                    var $url = 'api/foodEntries/' + $id;
                    var $data = {
                        id: $id,
                        date: $sql_date
                    };

                    return $http.post($url, $data);
                }
            }
        }
    });