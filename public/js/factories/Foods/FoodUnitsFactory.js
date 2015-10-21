angular.module('tracker')
    .factory('FoodUnitsFactory', function ($http) {
        return {
            insertFoodUnit: function () {
                var $url = 'insert/foodUnit';
                var $name = $("#create-new-food-unit").val();

                var $data = {
                    name: $name
                };

                $("#create-new-food-unit").val("");
                return $http.post($url, $data);
            },
            deleteFoodUnit: function ($id) {
                if (confirm("Are you sure you want to delete this unit?")) {
                    var $url = 'delete/foodUnit';
                    var $data = {
                        id: $id
                    };

                    return $http.post($url, $data);
                }
            },
            updateDefaultUnit: function ($food_id, $unit_id) {
                var $url = 'update/defaultUnit';

                var $data = {
                    food_id: $food_id,
                    unit_id: $unit_id
                };

                return $http.post($url, $data);
            },
        }
    });