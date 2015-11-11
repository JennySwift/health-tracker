app.factory('CaloriesFactory', function ($http) {
    return {

        getInfoForTheDay: function ($date) {
            var $url = 'api/calories/' + $date;
            return $http.get($url);
        },

    };
});