angular.module('tracker')
    .factory('FoodUnitsFactory', function ($http) {
        return {
            insert: function () {
                var $url = 'api/foodUnits';
                var $name = $("#create-new-food-unit").val();

                var $data = {
                    name: $name
                };

                $("#create-new-food-unit").val("");
                return $http.post($url, $data);
            },
            update: function ($unit) {
                var $url = 'api/foodUnits/' + $unit.id;
                var $data = {
                    unit: $unit
                };

                return $http.put($url, $data);
            },
            destroy: function ($id) {
                if (confirm("Are you sure you want to delete this unit?")) {
                    var $url = 'api/foodUnits/' + $id;

                    return $http.delete($url);
                }
            }
        }
    });