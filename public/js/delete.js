app.factory('deleteItem', function ($http) {
	return {
		deleteItem: function ($table, $item, $id, $func) {
			if(confirm("Are you sure you want to delete this " + $item + "?")) {
				var $url = 'ajax/delete.php';

				var $data = {
					table: $table,
					id: $id
				};

				return $http.post($url, $data);
			}
		}
	};
});