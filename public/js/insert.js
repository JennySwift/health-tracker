app.factory('insert', function ($http) {
	return {
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
		insert: function ($table) {
			var $input;
			var $url;

			if ($table === "recipes") {
				$input = $("#create-new-recipe");
				$url = 'insert/recipe';
			}
			else if ($table === "exercises") {
				$input = $("#create-new-exercise");
				$url = 'insert/exercise';
			}
			else if ($table === "food_units") {
				$input = $("#create-new-food-unit");
				$url = 'insert/foodUnit';
			}

			var $name = $($input).val();

			var $data = {
				table: $table,
				name: $name
			};

			$($input).val("");

			return $http.post($url, $data);
		},
		addItemToRecipe: function ($recipe, $food, $unit_id) {
			var $url = 'insert/recipeItem';
			var $table = "recipe_entries";
			var $input_to_focus = $("#food");

			var $data = {
				table: $table,
				recipe_id: $recipe.id,
				food_id: $food.id,
				quantity: $food.quantity,
				unit_id: $unit_id,
			};

			return $http.post($url, $data);
		},
		menuEntry: function ($sql_date, $menu_item, $quantity, $recipe_contents) {
			var $url = 'insert/menuEntry';
			var $table = "food_entries";
			var $input_to_focus = $("#food");
			var $unit_id = $("#food-unit option:selected").attr('data-unit-id');
			var $type = $menu_item.type;

			var $data = {
				table: $table,
				date: $sql_date,
				id: $menu_item.id,
				name: $menu_item.name,
				type: $type

			};

			if ($type === 'food') {
				$data.quantity = $quantity;
				$data.unit_id = $unit_id;
			}

			if ($type === 'recipe') {
				$data.recipe_contents = $recipe_contents;
			}

			$("#food").val("").focus();

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