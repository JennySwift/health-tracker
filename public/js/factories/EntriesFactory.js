app.factory('EntriesFactory', function ($http) {
	return {

		/**
		 * select
		 */

		getEntries: function ($date) {
			var $url = 'select/entries';
			var $data = {
				date: $date
			};
			
			return $http.post($url, $data);
		},
		getSpecificExerciseEntries: function ($sql_date, $exercise_id, $exercise_unit_id) {
			var $url = 'select/specificExerciseEntries';
			var $data = {
				date: $sql_date,
				exercise_id: $exercise_id,
				exercise_unit_id: $exercise_unit_id
			};
			
			return $http.post($url, $data);
		},

		/**
		 * insert
		 */
		
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
		insertExerciseEntry: function ($sql_date, $new_entry) {
			var $url = '/ExerciseEntries';
		
			var $data = {
				date: $sql_date,
				new_entry: $new_entry,
			};

			$("#exercise").val("").focus();

			return $http.post($url, $data);
		},
		insertExerciseSet: function ($sql_date, $exercise_id) {
			var $url = 'insert/exerciseSet';
			var $data = {
				date: $sql_date,
				exercise_id: $exercise_id
			};
			
			return $http.post($url, $data);
		},

		/**
		 * update
		 */
		
		/**
		 * delete
		 */
		
		/**
		 * Sending $exercise_id and $exercise_unit_id so that in the response I can return the info to update the popup.
		 * @param  {[type]} $id       [description]
		 * @param  {[type]} $sql_date [description]
		 * @return {[type]}           [description]
		 */
		deleteExerciseEntry: function ($id, $sql_date, $exercise_id, $exercise_unit_id) {
			if (confirm("Are you sure you want to delete this entry?")) {
				var $url = 'delete/exerciseEntry';
				var $data = {
					id: $id,
					date: $sql_date,
					exercise_id: $exercise_id,
					exercise_unit_id: $exercise_unit_id
				};
				
				return $http.post($url, $data);
			}
		},

		deleteFoodEntry: function ($id, $sql_date) {
			if (confirm("Are you sure you want to delete this entry?")) {
				var $url = 'delete/foodEntry';
				var $data = {
					id: $id,
					date: $sql_date
				};
				
				return $http.post($url, $data);
			}
		},
		deleteRecipeEntry: function ($sql_date, $recipe_id) {
			var $url = 'delete/recipeEntry';
			var $data = {
				recipe_id: $recipe_id,
				date: $sql_date
			};
			
			return $http.post($url, $data);
		},
	};
});
