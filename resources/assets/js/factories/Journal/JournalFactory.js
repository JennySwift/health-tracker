app.factory('JournalFactory', function ($http) {
    return {

        getJournalEntry: function ($sqlDate) {
            return $http.get('api/journal/' + $sqlDate);
        },

        filter: function () {
            var $typing = $("#filter-journal").val();
            var $url = 'select/filterJournalEntries';
            var $data = {
                typing: $typing
            };

            return $http.post($url, $data);
        },

        insert: function ($sqlDate) {
            var $url = 'api/journal';

            var $data = {
                date: $sqlDate,
                text: $("#journal-entry").html()
            };

            return $http.post($url, $data);
        },

        update: function ($entry) {
            var $url = 'api/journal/' + $entry.id;

            var $data = {
                text: $("#journal-entry").html()
            };

            return $http.put($url, $data);
        }
    };
});