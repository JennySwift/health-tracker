app.factory('select', function ($http) {
	return {
		displayFoodEntries: function ($sql_date) {
			var $url = 'select/foodEntries';
			var $table = "food_entries";

			var $data = {
				table: $table,
				date: $sql_date
			};

			return $http.post($url, $data);
		},
		displayExercises: function ($sql_date) {
			var $url = 'select/exercises';
			var $table = "exercise_entries";

			var $data = {
				table: $table,
				date: $sql_date
			};

			return $http.post($url, $data);
		},
		// displayFoodList: function () {
		// 	var $url = 'select/foodList';
		// 	var $table = "foods";

		// 	var $data = {
		// 		table: $table
		// 	};

		// 	return $http.post($url, $data);
		// },
		displayExerciseList: function () {
			var $url = 'select/exerciseList';
			var $table = "exercises";

			var $data = {
				table: $table
			};

			return $http.post($url, $data);
		},
		displayRecipeList: function () {
			var $url = 'select/recipeList';
			var $table = "recipes";

			var $data = {
				table: $table
			};

			return $http.post($url, $data);
		},
		displayUnitList: function () {
			var $url = 'select/unitList';
			var $table = "food_units";

			var $data = {
				table: $table
			};

			// return $http.post($url, $data);
			return $http.post($url, $data);
		},
		getAllFoodsWithUnits: function () {
			var $url = 'select/allFoodsWithUnits';
			var $table = "all_foods_with_units";
			var $data = {
				table: $table
			};

			return $http.post($url, $data);
		},
		displayWeight: function ($sql_date) {
			var $url = 'select/weight';
			var $table = "weight";
			
			var $data = {
				table: $table,
				date: $sql_date
			};

			return $http.post($url, $data);
		},
		displayRecipeContents: function ($recipe_id, $recipe_name) {
			var $url = 'select/recipeContents';
			var $table = "recipe_entries";

			var $data = {
				table: $table,
				recipe_id: $recipe_id
			};

			return $http.post($url, $data);
		},
		getMenu: function ($foods, $recipes) {
			var $scope_menu = [];
			var $menu = $foods.concat($recipes);
			
			for (var i = 0; i < $menu.length; i++) {
				var $iteration = $menu[i];
				if ($iteration.food_id) {
					$scope_menu.push(
						{
							type: 'food',
							id: $iteration.food_id,
							name: $iteration.food_name
						}
					);
				}
				else if ($iteration.recipe_id) {
					$scope_menu.push(
						{
							type: 'recipe',
							id: $iteration.recipe_id,
							name: $iteration.recipe_name
						}
					);
				}
			}
			return $scope_menu;
		}
	};
});