app.factory('update', function ($http) {
	return {
		updateDefaultUnit: function ($unit_id, $food_id, $food_name) {
			var $url = 'ajax/update.php';
			var $column = 'default_unit';

			var $data = {
				column: $column,
				food_id: $food_id,
				food_name: $food_name,
				unit_id: $unit_id
			};

			return $http.post($url, $data);
		},
		updateCalories: function ($food_id, $food_name, $unit_id, $calories) {
			var $url = 'ajax/update.php';
			var $column = 'calories';

			var $data = {
				column: $column,
				food_id: $food_id,
				food_name: $food_name,
				unit_id: $unit_id,
				calories: $calories
			};

			return $http.post($url, $data);
		}
	};
});