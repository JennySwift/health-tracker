app.factory('select', function ($http) {
	return {
		pageLoad: function ($sql_date) {
			var $url = 'select/pageLoad';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		exerciseSeriesHistory: function ($series_id) {
			var $url = 'select/exerciseSeriesHistory';
			var $data = {
				series_id: $series_id
			};
			
			return $http.post($url, $data);
		},
		journalEntry: function ($sql_date) {
			var $url = 'select/journalEntry';
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
		autocompleteFood: function ($typing) {
			var $url = 'select/autocompleteFood';
			var $data = {
				typing: $typing
			};
			
			return $http.post($url, $data);
		},
		autocompleteMenu: function () {
			var $typing = $("#menu").val();
			var $url = 'select/autocompleteMenu';
			var $data = {
				typing: $typing
			};
			
			return $http.post($url, $data);
		},
		// autocompleteMenu: function ($menu) {
		// 	var $typing = $("#food").val();
		// 	var $menu_autocomplete = [];

		// 	for (var i = 0; i < $menu.length; i++) {
		// 		var $iteration = $menu[i];
		// 		var $iteration_name = $iteration.name.toLowerCase();
		// 		if ($iteration_name.indexOf($typing.toLowerCase()) !== -1) {
		// 			$menu_autocomplete.push($iteration);
		// 		}
		// 	}
		// 	return $menu_autocomplete;
		// },
		entries: function ($sql_date) {
			var $url = 'select/entries';
			var $data = {
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		specificExerciseEntries: function ($sql_date, $exercise_id, $exercise_unit_id) {
			var $url = 'select/specificExerciseEntries';
			var $data = {
				date: $sql_date,
				exercise_id: $exercise_id,
				exercise_unit_id: $exercise_unit_id
			};
			
			return $http.post($url, $data);
		},
		foodInfo: function ($id) {
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
		filterRecipes: function ($tag_ids) {
			var $typing = $("#filter-recipes").val();
			var $url = 'select/filterRecipes';
			var $table = "recipes";

			var $data = {
				typing: $typing,
				tag_ids: $tag_ids
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
		recipeContents: function ($recipe_id) {
			var $url = 'select/recipeContents';

			var $data = {
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