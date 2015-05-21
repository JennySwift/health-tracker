app.factory('foods', function ($http) {
	return {
		/**
		 * select
		 */
		
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
		getAllFoodsWithUnits: function () {
			var $url = 'select/allFoodsWithUnits';
			var $table = "all_foods_with_units";

			return $http.post($url);
		},
		getRecipeContents: function ($recipe_id) {
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
		},
		getFoodInfo: function ($id) {
			var $url = 'select/foodInfo';
			
			var $data = {
				food_id: $id
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

		/**
		 * insert
		 */
		
		insertRecipeMethod: function ($recipe_id, $steps) {
			var $url = 'insert/recipeMethod';
			var $data = {
				recipe_id: $recipe_id,
				steps: $steps
			};
			
			return $http.post($url, $data);
		},
		insertQuickRecipe: function ($recipe_name, $contents, $steps, $check_similar_names) {
			var $url = 'insert/quickRecipe';
			var $data = {
				recipe_name: $recipe_name,
				contents: $contents,
				steps: $steps,
				check_similar_names: $check_similar_names
			};
			
			return $http.post($url, $data);
		},
		insertFoodIntoRecipe: function ($data) {
			var $url = 'insert/foodIntoRecipe';

			// var $data = {
			// 	recipe_id: $recipe.id,
			// 	food_id: $food.id,
			// 	quantity: $food.quantity,
			// 	unit_id: $unit_id,
			// };

			return $http.post($url, $data);
		},
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
		insertRecipeEntry: function ($sql_date, $recipe_id, $recipe_contents) {
			var $url = 'insert/recipeEntry';
		
			var $data = {
				date: $sql_date,
				recipe_id: $recipe_id,
				recipe_contents: $recipe_contents
			};

			$("#menu").val("").focus();

			return $http.post($url, $data);
		},
		insertRecipe: function ($name) {
			var $url = 'insert/recipe';
			var $data = {
				name: $name
			};
			
			return $http.post($url, $data);
		},
		insertFood: function () {
			var $url = 'insert/food';
			var $name = $("#create-new-food").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-food").val("");		
			return $http.post($url, $data);
		},
		insertUnitInCalories: function ($food_id, $unit_id) {
			var $url = 'insert/unitInCalories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
			};

			return $http.post($url, $data);
		},
		insertTagsIntoRecipe: function ($recipe_id, $tags) {
			var $url = 'insert/tagsIntoRecipe';
			var $data = {
				recipe_id: $recipe_id,
				tags: $tags
			};
			
			return $http.post($url, $data);
		},
		
		/**
		 * update
		 */
		
		updateDefaultUnit: function ($food_id, $unit_id) {
			var $url = 'update/defaultUnit';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};

			return $http.post($url, $data);
		},
		updateCalories: function ($food_id, $unit_id, $calories) {
			var $url = 'update/calories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
				calories: $calories
			};

			return $http.post($url, $data);
		},
		updateRecipeMethod: function ($recipe_id, $steps) {
			//deletes the existing method then inserts the edited method
			var $url = 'update/recipeMethod';
			var $data = {
				recipe_id: $recipe_id,
				steps: $steps
			};
			
			return $http.post($url, $data);
		},

		/**
		 * delete
		 */

		deleteFoodFromRecipe: function ($id, $recipe_id) {
			if (confirm("Are you sure you want to remove this food from your recipe?")) {
				var $url = 'delete/foodFromRecipe';
				var $data = {
					id: $id,
					recipe_id: $recipe_id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteRecipe: function ($id) {
			if (confirm("Are you sure you want to delete this recipe?")) {
				var $url = 'delete/recipe';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteFood: function ($id) {
			if (confirm("Are you sure you want to delete this food?")) {
				var $url = 'delete/food';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteUnitFromCalories: function ($food_id, $unit_id) {
			var $url = 'delete/unitFromCalories';
			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};
			
			return $http.post($url, $data);
		},
	};
});