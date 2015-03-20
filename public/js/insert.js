app.factory('insert', function ($http) {
	return {
		journalEntry: function ($sql_date, $text) {
			var $url = 'insert/journalEntry';
			var $data = {
				date: $sql_date,
				text: $text
			};
			
			return $http.post($url, $data);
		},
		recipe: function ($name) {
			var $url = 'insert/recipe';
			var $data = {
				name: $name
			};
			
			return $http.post($url, $data);
		},
		food: function () {
			var $url = 'insert/food';
			var $name = $("#create-new-food").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-food").val("");		
			return $http.post($url, $data);
		},
		exercise: function () {
			var $url = 'insert/exercise';
			var $name = $("#create-new-exercise").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-exercise").val("");		
			return $http.post($url, $data);
		},
		exerciseUnit: function () {
			var $url = 'insert/exerciseUnit';
			var $name = $("#create-new-exercise-unit").val();
			
			var $data = {
				name: $name
			};

			$("#create-new-exercise-unit").val("");		
			return $http.post($url, $data);
		},
		unitInCalories: function ($food_id, $unit_id, $checked_previously) {
			var $url = 'insert/unitInCalories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
				checked_previously: $checked_previously
			};

			return $http.post($url, $data);
		},
		// insert: function ($table) {
		// 	var $input;
		// 	var $url;

		// 	if ($table === "recipes") {
		// 		$input = $("#create-new-recipe");
		// 		$url = 'insert/recipe';
		// 	}
		// 	else if ($table === "exercises") {
		// 		$input = $("#create-new-exercise");
		// 		$url = 'insert/exercise';
		// 	}
		// 	else if ($table === "food_units") {
		// 		$input = $("#create-new-food-unit");
		// 		$url = 'insert/foodUnit';
		// 	}

		// 	var $name = $($input).val();

		// 	var $data = {
		// 		table: $table,
		// 		name: $name
		// 	};

		// 	$($input).val("");

		// 	return $http.post($url, $data);
		// },
		foodIntoRecipe: function ($data) {
			var $url = 'insert/foodIntoRecipe';

			// var $data = {
			// 	recipe_id: $recipe.id,
			// 	food_id: $food.id,
			// 	quantity: $food.quantity,
			// 	unit_id: $unit_id,
			// };

			return $http.post($url, $data);
		},
		menuEntry: function ($sql_date, $new_entry, $recipe_contents) {
			var $url = 'insert/menuEntry';
			var $type = 'food';
	
			var $data = {
				date: $sql_date,
				type: $type,
				new_entry: $new_entry,
			};

			$("#food").val("").focus();

			return $http.post($url, $data);
		},
		exerciseEntry: function ($sql_date, $new_entry) {
			var $url = 'insert/exerciseEntry';
		
			var $data = {
				date: $sql_date,
				new_entry: $new_entry,
			};

			$("#exercise").val("").focus();

			return $http.post($url, $data);
		},
		weight: function ($sql_date) {
			var $url = 'insert/weight';
			var $weight = $("#weight").val();

			var $data = {
				date: $sql_date,
				weight: $weight
			};

			return $http.post($url, $data);
		}
	};
});