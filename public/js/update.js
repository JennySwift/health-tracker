app.factory('update', function ($http) {
	return {
		defaultUnit: function ($food_id, $unit_id) {
			var $url = 'update/defaultUnit';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id
			};

			return $http.post($url, $data);
		},
		calories: function ($food_id, $unit_id, $calories) {
			var $url = 'update/calories';

			var $data = {
				food_id: $food_id,
				unit_id: $unit_id,
				calories: $calories
			};

			return $http.post($url, $data);
		}
	};
});