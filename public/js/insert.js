app.factory('insert', function ($http) {
	return {
		insert: function ($table) {
			var $input;

			if ($table === "foods") {
				$input = $("#create-new-food");
			}
			else if ($table === "recipes") {
				$input = $("#create-new-recipe");
			}
			else if ($table === "exercises") {
				$input = $("#create-new-exercise");
			}
			else if ($table === "food_units") {
				$input = $("#create-new-food-unit");
			}

			var $name = $($input).val();
			var $url = 'ajax/insert.php';

			var $data = {
				table: $table,
				name: $name
			};

			$($input).val("");

			return $http.post($url, $data);
		},
		addItemToRecipe: function ($recipe, $food, $unit_id) {
			var $url = 'ajax/insert.php';
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
		addMenuEntry: function ($sql_date, $menu_item, $quantity, $recipe_contents) {
			var $url = 'ajax/insert.php';
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
		enterWeight: function ($sql_date) {
			var $url = 'ajax/insert.php';
			var $table = 'weight';
			var $weight = $("#weight").val();

			var $data = {
				table: $table,
				date: $sql_date,
				weight: $weight
			};

			return $http.post($url, $data);
		}
	};
});