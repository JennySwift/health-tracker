app.factory('deleteItem', function ($http) {
	return {
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