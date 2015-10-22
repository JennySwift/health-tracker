app.factory('EntriesFactory', function ($http) {
    return {

        getEntries: function ($date) {
            var $url = 'select/entries';
            var $data = {
                date: $date
            };

            return $http.post($url, $data);
        }

    };
});
