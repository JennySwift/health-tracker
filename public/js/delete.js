app.factory('deleteItem', function ($http) {
	return {
		foodFromRecipe: function ($id, $recipe_id) {
			if (confirm("Are you sure you want to remove this food from your recipe?")) {
				var $url = 'delete/foodFromRecipe';
				var $data = {
					id: $id,
					recipe_id: $recipe_id
				};
				
				return $http.post($url, $data);
			}
		},
		tagFromExercise: function ($exercise_id, $tag_id) {
			var $url = 'delete/tagFromExercise';
			var $data = {
				exercise_id: $exercise_id,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		exerciseTag: function ($id) {
			if (confirm("Are you sure you want to delete this tag?")) {
				var $url = 'delete/exerciseTag';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		recipe: function ($id) {
			if (confirm("Are you sure you want to delete this recipe?")) {
				var $url = 'delete/recipe';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		food: function ($id) {
			if (confirm("Are you sure you want to delete this food?")) {
				var $url = 'delete/food';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		exercise: function ($id) {
			if (confirm("Are you sure you want to delete this exercise?")) {
				var $url = 'delete/exercise';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		exerciseUnit: function ($id) {
			if (confirm("Are you sure you want to delete this unit?")) {
				var $url = 'delete/exerciseUnit';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		foodUnit: function ($id) {
			if (confirm("Are you sure you want to delete this unit?")) {
				var $url = 'delete/foodUnit';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		unitFromCalories: function ($food_id, $unit_id) {
			var $url = 'delete/unitFromCalories';
			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};
			
			return $http.post($url, $data);
		},
		foodEntry: function ($id, $sql_date) {
			if (confirm("Are you sure you want to delete this entry?")) {
				var $url = 'delete/foodEntry';
				var $data = {
					id: $id,
					date: $sql_date
				};
				
				return $http.post($url, $data);
			}
		},
		recipeEntry: function ($sql_date, $recipe_id) {
			var $url = 'delete/recipeEntry';
			var $data = {
				recipe_id: $recipe_id,
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
		exerciseEntry: function ($id, $sql_date) {
			if (confirm("Are you sure you want to delete this entry?")) {
				var $url = 'delete/exerciseEntry';
				var $data = {
					id: $id,
					date: $sql_date
				};
				
				return $http.post($url, $data);
			}
		},
		deleteItem: function ($table, $item, $id, $func) {
			if(confirm("Are you sure you want to delete this " + $item + "?")) {
				var $url = 'delete/item';

				var $data = {
					table: $table,
					id: $id
				};

				return $http.post($url, $data);
			}
		}
	};
});