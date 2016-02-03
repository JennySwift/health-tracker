//app.factory('FoodsFactory', function ($http) {
//	return {
//
//        getAllFoodsWithUnits: function () {
//            var $url = 'api/foods';
//
//            return $http.get($url);
//        },
//
//        getMenu: function ($foods, $recipes) {
//            var $scope_menu = [];
//            var $menu = $foods.concat($recipes);
//
//            for (var i = 0; i < $menu.length; i++) {
//                var $iteration = $menu[i];
//                if ($iteration.id) {
//                    $scope_menu.push(
//                        {
//                            type: 'food',
//                            id: $iteration.id,
//                            name: $iteration.name
//                        }
//                    );
//                }
//                else if ($iteration.recipe_id) {
//                    $scope_menu.push(
//                        {
//                            type: 'recipe',
//                            id: $iteration.recipe_id,
//                            name: $iteration.recipe_name
//                        }
//                    );
//                }
//            }
//            return $scope_menu;
//        },
//        getFoodInfo: function ($food) {
//            var $url = $food.path;
//
//            return $http.get($url);
//        },
//        displayFoodEntries: function ($sql_date) {
//            var $url = 'select/foodEntries';
//            var $table = "food_entries";
//
//            var $data = {
//                table: $table,
//                date: $sql_date
//            };
//
//            return $http.post($url, $data);
//        },
//
//        insertFood: function () {
//            var $url = 'api/foods';
//            var $name = $("#create-new-food").val();
//
//            var $data = {
//                name: $name
//            };
//
//            $("#create-new-food").val("");
//            return $http.post($url, $data);
//        },
//
//        insertUnitInCalories: function ($food_id, $unit_id) {
//            var $url = 'insert/unitInCalories';
//
//            var $data = {
//                food_id: $food_id,
//                unit_id: $unit_id,
//            };
//
//            return $http.post($url, $data);
//        },
//
//        updateDefaultUnit: function ($food_id, $unit_id) {
//            var $url = 'api/foods/' + $food_id;
//
//            var $data = {
//                food_id: $food_id,
//                unit_id: $unit_id
//            };
//
//            return $http.put($url, $data);
//        },
//
//        updateCalories: function ($food_id, $unit_id, $calories) {
//            var $url = 'update/calories';
//
//            var $data = {
//                food_id: $food_id,
//                unit_id: $unit_id,
//                calories: $calories
//            };
//
//            return $http.post($url, $data);
//        },
//
//        destroy: function ($food) {
//            if (confirm("Are you sure you want to delete this food?")) {
//                var $url = 'api/foods/' + $food.id;
//
//                return $http.delete($url);
//            }
//        },
//
//        deleteUnitFromCalories: function ($food_id, $unit_id) {
//            var $url = 'delete/unitFromCalories';
//            var $data = {
//                food_id: $food_id,
//                unit_id: $unit_id
//            };
//
//            return $http.post($url, $data);
//        },
//
//	};
//});