app.factory('select', function ($http) {
	return {
		pageLoad: function ($sql_date) {
			var $url = 'select/pageLoad';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		autocompleteExercise: function () {
			var $typing = $("#exercise").val();
			var $url = 'select/autocompleteExercise';
			var $data = {
				exercise: $typing
			};
			
			return $http.post($url, $data);
		},
		autocompleteFood: function ($foods, $typing) {
			$food_autocomplete = [];

			for (var i = 0; i < $foods.length; i++) {
				var $iteration = $foods[i];
				var $iteration_name = $iteration.food_name.toLowerCase();
				if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
					$food_autocomplete.push($iteration);
				}
			}
			return $food_autocomplete;
		},
		autocompleteMenu: function ($menu) {
			var $typing = $("#food").val();
			var $menu_autocomplete = [];

			for (var i = 0; i < $menu.length; i++) {
				var $iteration = $menu[i];
				var $iteration_name = $iteration.name.toLowerCase();
				if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
					$menu_autocomplete.push($iteration);
				}
			}
			return $menu_autocomplete;
		},
		entries: function ($sql_date) {
			var $url = 'select/entries';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		foodInfo: function ($id, $name) {
			var $url = 'select/foodInfo';
			
			var $data = {
				id: $id
			};

			return $http.post($url, $data);
		},
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
		displayFoodList: function () {
			var $url = 'select/foodList';
			var $table = "foods";

			var $data = {
				table: $table
			};

			return $http.post($url, $data);
		},
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
				if ($iteration.id) {
					$scope_menu.push(
						{
							type: 'food',
							id: $iteration.id,
							name: $iteration.name
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