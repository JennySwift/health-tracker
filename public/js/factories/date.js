app.factory('date', function ($http) {
	return {
		changeDate: function ($keycode, $date) {
			$date = Date.parseExact($date, ['d MMM yyyy', 'd/M/yyyy', 'd MMM yy', 'd/M/yy']).toString('dd/MM/yyyy');
			return $date;
		},
		today: function () {
			var $date = Date.parse('today').toString('dd/MM/yyyy');
			return $date;
		},
		goToDate: function ($previous_date, $number) {
			var $date = Date.parse($previous_date).addDays($number).toString('dd/MM/yyyy');
			return $date;
		}

	};
});
