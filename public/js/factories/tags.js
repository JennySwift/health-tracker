app.factory('tags', function ($http) {
	return {
		/**
		 * select
		 */
		
		/**
		 * insert
		 */
		
		insertTagInExercise: function ($exercise_id, $tag_id) {
			var $url = 'insert/tagInExercise';
			var $data = {
				exercise_id: $exercise_id,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		insertTagsInExercise: function ($exercise_id, $tags) {
			var $url = 'insert/tagsInExercise';
			var $data = {
				exercise_id: $exercise_id,
				tags: $tags
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
		insertExerciseTag: function () {
			var $name = $("#create-exercise-tag").val();
			var $url = 'insert/exerciseTag';
			var $data = {
				name: $name
			};

			$("#create-exercise-tag").val("");
			
			return $http.post($url, $data);
		},
		insertRecipeTag: function () {
			var $name = $("#create-new-recipe-tag").val();
			var $url = 'insert/recipeTag';
			var $data = {
				name: $name
			};

			$("#create-new-recipe-tag").val("");
			
			return $http.post($url, $data);
		},

		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
		deleteTagFromExercise: function ($exercise_id, $tag_id) {
			var $url = 'delete/tagFromExercise';
			var $data = {
				exercise_id: $exercise_id,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		deleteExerciseTag: function ($id) {
			if (confirm("Are you sure you want to delete this tag?")) {
				var $url = 'delete/exerciseTag';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
		deleteRecipeTag: function ($id) {
			if (confirm("Are you sure you want to delete this tag?")) {
				var $url = 'delete/recipeTag';
				var $data = {
					id: $id
				};
				
				return $http.post($url, $data);
			}
		},
	};
});